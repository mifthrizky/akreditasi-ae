<?php

namespace App\Services;

use App\Models\Submission;
use App\Models\SubmissionItem;

class SkorService
{
    /**
     * Calculate skor for a submission based on filled template items
     * 
     * Formula: (sum of filled item bobot / sum of required item bobot) × kriteria bobot
     * 
     * Filled rules:
     * - checklist: nilai_checklist = true
     * - upload: nilai_teks contains non-empty path
     * - numerik: nilai_numerik >= nilai_min_numerik (if min specified, else not null)
     * - narasi: nilai_teks is not empty/null
     */
    public function calculate(Submission $submission): float
    {
        // Get all template items for this kriteria
        $templateItems = $submission->kriteria->templateItems()->get();

        if ($templateItems->isEmpty()) {
            return 0;
        }

        $totalRequiredBobot = 0;
        $filledBobot = 0;

        foreach ($templateItems as $template) {
            // Only count required items in denominator
            if ($template->wajib) {
                $totalRequiredBobot += $template->bobot;
            }

            // Check if this item is filled
            $submissionItem = $submission->items()
                ->where('template_item_id', $template->template_id)
                ->first();

            if ($this->isItemFilled($submissionItem, $template)) {
                $filledBobot += $template->bobot;
            }
        }

        // Calculate percentage (0-1)
        if ($totalRequiredBobot == 0) {
            $percentage = 0;
        } else {
            $percentage = $filledBobot / $totalRequiredBobot;
        }

        // Apply kriteria bobot
        $finalScore = $percentage * $submission->kriteria->bobot;

        return round($finalScore, 2);
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
     * Aggregate scores from level-1 kriteria to level-0 parent
     * Returns array of parent_kriteria_id => aggregated_skor
     */
    public function aggregateToParent(int $prodi_id, string $status = 'diterima'): array
    {
        $scores = $this->calculateAllForProdi($prodi_id, $status);

        // Get all level-1 kriteria with their parents
        $kriterias = \App\Models\Kriteria::where('level', 1)
            ->with('parent')
            ->get();

        $aggregated = [];
        foreach ($kriterias as $kriteria) {
            if (!isset($scores[$kriteria->kriteria_id])) {
                continue;
            }

            $parentId = $kriteria->parent_id;
            if (!isset($aggregated[$parentId])) {
                $aggregated[$parentId] = ['sum' => 0, 'count' => 0];
            }

            $aggregated[$parentId]['sum'] += $scores[$kriteria->kriteria_id];
            $aggregated[$parentId]['count']++;
        }

        // Calculate average
        $result = [];
        foreach ($aggregated as $parentId => $data) {
            $result[$parentId] = $data['count'] > 0
                ? round($data['sum'] / $data['count'], 2)
                : 0;
        }

        return $result;
    }

    /**
     * Calculate total skor across all kriteria (average of all level-0 parents)
     */
    public function calculateTotalForProdi(int $prodi_id, string $status = 'diterima'): float
    {
        $aggregated = $this->aggregateToParent($prodi_id, $status);

        if (empty($aggregated)) {
            return 0;
        }

        $total = array_sum($aggregated);
        return round($total / count($aggregated), 2);
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
     * Get status color based on score percentage
     */
    public function getStatusColor(float $skor): string
    {
        if ($skor < 50) {
            return 'red';     // RED: Critical
        } elseif ($skor < 80) {
            return 'yellow';   // YELLOW: Partial
        } else {
            return 'green';    // GREEN: Good
        }
    }

    /**
     * Get status label
     */
    public function getStatusLabel(float $skor): string
    {
        if ($skor < 50) {
            return 'Kritis';
        } elseif ($skor < 80) {
            return 'Sebagian';
        } else {
            return 'Baik';
        }
    }
}
