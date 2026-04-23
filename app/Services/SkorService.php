<?php

namespace App\Services;

use App\Models\Submission;
use App\Models\SubmissionItem;

class SkorService
{
    /**
     * Calculate skor for a submission based on filled template items
     * 
     * Formula per PRD: Skor = SUM(bobot_item × filled_status) / SUM(bobot_item)
     * - Narasi items (bobot=0) are EXCLUDED from calculation
     * - Only required items with bobot > 0 count toward score
     * 
     * Filled rules:
     * - checklist: nilai_checklist = true
     * - upload: dokumen exists (nilai_teks contains non-empty path)
     * - numerik: nilai_numerik >= nilai_min_numerik (if min specified, else not null)
     * - narasi: EXCLUDED (bobot=0, does not contribute to score)
     */
    public function calculate(Submission $submission): float
    {
        // Get all template items for this kriteria, excluding narasi items
        $templateItems = $submission->kriteria->templateItems()
            ->where('tipe', '!=', 'narasi')
            ->get();

        if ($templateItems->isEmpty()) {
            return 0;
        }

        $totalBobot = 0;
        $filledBobot = 0;

        foreach ($templateItems as $template) {
            // Count ALL items with bobot > 0 toward denominator
            // (narasi already excluded by query, and narasi items have bobot=0)
            // wajib flag is for form validation (required/optional input), not scoring
            if ($template->bobot > 0) {
                $totalBobot += $template->bobot;

                // Check if this item is filled
                $submissionItem = $submission->items()
                    ->where('template_item_id', $template->template_id)
                    ->first();

                if ($this->isItemFilled($submissionItem, $template)) {
                    $filledBobot += $template->bobot;
                }
            }
        }

        // Calculate percentage: filledBobot / totalBobot
        if ($totalBobot == 0) {
            return 0;
        }

        $percentage = ($filledBobot / $totalBobot) * 100; // 0-100 range

        return round($percentage, 2);
    }

    /**
     * Calculate scores for all submissions in a prodi (for laporan)
     * Returns array of kriteria_id => skor
     */
    public function calculateAllForProdi(int $prodi_id, string $status = 'diterima'): array
    {
        $submissions = Submission::where('prodi_id', $prodi_id)
            ->where('status', $status)
            ->with('kriteria', 'items')
            ->get();

        $scores = [];
        foreach ($submissions as $submission) {
            $scores[$submission->kriteria_id] = $this->calculate($submission);
        }

        return $scores;
    }

    /**
     * General method to aggregate child scores to their parent level using Weighted Average (Bobot Kriteria)
     * e.g. calculating Level 1 from Level 2, or Level 0 from Level 1
     */
    public function aggregateToParentLevel(array $childScores, int $targetLevel): array
    {
        // Get all children that belong to the target level's parents
        // if targetLevel is 0, children are level 1
        // if targetLevel is 1, children are level 2
        $children = \App\Models\Kriteria::where('level', $targetLevel + 1)->get();

        $aggregated = [];
        foreach ($children as $child) {
            if (!isset($childScores[$child->kriteria_id])) {
                continue;
            }

            $parentId = $child->parent_id;
            if (!$parentId) {
                continue;
            }

            if (!isset($aggregated[$parentId])) {
                $aggregated[$parentId] = ['sum_score_x_bobot' => 0, 'sum_bobot' => 0];
            }

            // Weighted average: Skor_i * Bobot_i
            $aggregated[$parentId]['sum_score_x_bobot'] += $childScores[$child->kriteria_id] * $child->bobot;
            $aggregated[$parentId]['sum_bobot'] += $child->bobot;
        }

        $result = [];
        foreach ($aggregated as $parentId => $data) {
            $result[$parentId] = $data['sum_bobot'] > 0
                ? round($data['sum_score_x_bobot'] / $data['sum_bobot'], 2)
                : 0;
        }

        return $result;
    }

    /**
     * Calculate scores for all Level 0 Kriteria (for Radar Chart)
     */
    public function aggregateToLevel0(int $prodi_id, string $status = 'diterima'): array
    {
        // Level 2 scores (submissions)
        $level2Scores = $this->calculateAllForProdi($prodi_id, $status);

        // Aggregate Level 2 -> Level 1
        $level1Scores = $this->aggregateToParentLevel($level2Scores, 1);

        // Aggregate Level 1 -> Level 0
        return $this->aggregateToParentLevel($level1Scores, 0);
    }

    /**
     * Get legacy method signature so things don't break, redirecting to new logic
     */
    public function aggregateToParent(int $prodi_id, string $status = 'diterima'): array
    {
        return $this->aggregateToLevel0($prodi_id, $status);
    }

    /**
     * Calculate total skor across all kriteria (Weighted average of all level-0 parents)
     */
    public function calculateTotalForProdi(int $prodi_id, string $status = 'diterima'): float
    {
        $level0Scores = $this->aggregateToLevel0($prodi_id, $status);

        if (empty($level0Scores)) {
            return 0;
        }

        $level0Kriteria = \App\Models\Kriteria::where('level', 0)->get();

        $totalSum = 0;
        $totalBobot = 0;

        foreach ($level0Kriteria as $k0) {
            if (isset($level0Scores[$k0->kriteria_id])) {
                $totalSum += $level0Scores[$k0->kriteria_id] * $k0->bobot;
                $totalBobot += $k0->bobot;
            }
        }

        if ($totalBobot == 0) {
            return 0;
        }

        return round($totalSum / $totalBobot, 2);
    }

    /**
     * Check if a submission item is filled based on template type
     */
    private function isItemFilled(?SubmissionItem $item, $template): bool
    {
        if (!$item) {
            return false;
        }

        switch ($template->tipe) {
            case 'checklist':
                return (bool) $item->nilai_checklist;

            case 'upload':
                // Check if file path exists (uploaded)
                return !empty($item->nilai_teks);

            case 'numerik':
                // Check if value meets minimum or is not null
                if (is_null($item->nilai_numerik)) {
                    return false;
                }
                if (!is_null($template->nilai_min_numerik)) {
                    return $item->nilai_numerik >= $template->nilai_min_numerik;
                }
                return true;

            case 'narasi':
                return !empty(trim($item->nilai_teks ?? ''));

            default:
                return false;
        }
    }

    /**
     * Get status color based on score percentage (0-100 range)
     * Thresholds per PRD:
     * - ≥80%: Sangat Siap (green)
     * - 75-79.99%: Siap (light green)
     * - 60-74.99%: Hampir Siap (yellow)
     * - <60%: Perlu Perbaikan (red)
     */
    public function getStatusColor(float $skor): string
    {
        if ($skor >= 80) {
            return 'green';          // GREEN: Sangat Siap
        } elseif ($skor >= 75) {
            return 'light-green';    // LIGHT GREEN: Siap
        } elseif ($skor >= 60) {
            return 'yellow';         // YELLOW: Hampir Siap
        } else {
            return 'red';            // RED: Perlu Perbaikan
        }
    }

    /**
     * Get status label based on score percentage (0-100 range)
     */
    public function getStatusLabel(float $skor): string
    {
        if ($skor >= 80) {
            return 'Sangat Siap';
        } elseif ($skor >= 75) {
            return 'Siap';
        } elseif ($skor >= 60) {
            return 'Hampir Siap';
        } else {
            return 'Perlu Perbaikan';
        }
    }
}
