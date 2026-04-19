<?php

namespace App\Services;

use App\Models\Submission;
use App\Models\Kriteria;

class GapAnalysisService
{
    protected $skorService;

    public function __construct(SkorService $skorService)
    {
        $this->skorService = $skorService;
    }

    /**
     * Analyze gaps for a single submission
     * Returns array with unfilled items, severity, and recommendations
     */
    public function analyzeSubmission(Submission $submission): array
    {
        $kriteria = $submission->kriteria;
        $skor = $this->skorService->calculate($submission);

        $gaps = [];
        $unfilled = [];

        // Get all template items
        $templateItems = $kriteria->templateItems()->get();

        foreach ($templateItems as $template) {
            $submissionItem = $submission->items()
                ->where('template_item_id', $template->template_id)
                ->first();

            // Check if item is not filled
            if (!$this->isItemFilled($submissionItem, $template)) {
                // Only list if required
                if ($template->wajib) {
                    $unfilled[] = [
                        'template_id' => $template->template_id,
                        'label' => $template->label,
                        'type' => $template->tipe,
                        'hint' => $template->hint,
                        'bobot' => $template->bobot,
                    ];
                }
            }
        }

        $severity = $this->getSeverity($skor);

        return [
            'submission_id' => $submission->submission_id,
            'kriteria_id' => $kriteria->kriteria_id,
            'kriteria_kode' => $kriteria->kode,
            'kriteria_nama' => $kriteria->nama,
            'skor' => $skor,
            'skor_percent' => round($skor * 100, 2),
            'severity' => $severity,
            'status_color' => $this->skorService->getStatusColor($skor),
            'status_label' => $this->skorService->getStatusLabel($skor),
            'unfilled_items' => $unfilled,
            'unfilled_count' => count($unfilled),
            'recommendation' => $this->getRecommendation($unfilled, $skor),
        ];
    }

    /**
     * Analyze gaps for entire prodi (for laporan)
     * Returns array of gaps for all kriteria with status='diterima'
     */
    public function analyzeProdi(int $prodi_id): array
    {
        $submissions = Submission::where('prodi_id', $prodi_id)
            ->where('status', 'diterima')
            ->with('kriteria', 'items.templateItem')
            ->get();

        $gaps = [];

        foreach ($submissions as $submission) {
            $gap = $this->analyzeSubmission($submission);
            $gaps[] = $gap;
        }

        return $gaps;
    }

    /**
     * Get gaps grouped by parent kriteria (level 0)
     * Useful for laporan display
     */
    public function analyzeProdiByParent(int $prodi_id): array
    {
        $gaps = $this->analyzeProdi($prodi_id);

        // Get all level-0 kriteria
        $parents = Kriteria::where('level', 0)
            ->orderBy('urutan')
            ->get()
            ->keyBy('kriteria_id');

        $grouped = [];

        foreach ($parents as $parentId => $parent) {
            $grouped[$parentId] = [
                'parent_kriteria' => [
                    'kriteria_id' => $parent->kriteria_id,
                    'kode' => $parent->kode,
                    'nama' => $parent->nama,
                ],
                'gaps' => [],
            ];
        }

        // Assign gaps to their parent
        foreach ($gaps as $gap) {
            $kriteria = Kriteria::find($gap['kriteria_id']);
            if ($kriteria && $kriteria->parent_id) {
                $grouped[$kriteria->parent_id]['gaps'][] = $gap;
            }
        }

        // Remove parent entries with no gaps
        $grouped = array_filter($grouped, fn($item) => !empty($item['gaps']));

        return $grouped;
    }

    /**
     * Severity level based on score
     * RED: 0-50%, YELLOW: 50-80%, GREEN: 80%+
     */
    private function getSeverity(float $skor): string
    {
        if ($skor < 0.5) {
            return 'red';
        } elseif ($skor < 0.8) {
            return 'yellow';
        } else {
            return 'green';
        }
    }

    /**
     * Generate recommendation based on unfilled items
     */
    private function getRecommendation(array $unfilledItems, float $skor): string
    {
        if (count($unfilledItems) === 0) {
            return 'Semua item sudah terpenuhi.';
        }

        if ($skor < 0.5) {
            return sprintf(
                'KRITIS: Lengkapi semua %d item yang hilang segera untuk memenuhi standar minimum.',
                count($unfilledItems)
            );
        } elseif ($skor < 0.8) {
            return sprintf(
                'Sebagian sudah terpenuhi. Lengkapi %d item tertinggal untuk mencapai standar optimal.',
                count($unfilledItems)
            );
        } else {
            return sprintf(
                'Hampir sempurna. Selesaikan %d item tersisa untuk hasil maksimal.',
                count($unfilledItems)
            );
        }
    }

    /**
     * Check if submission item is filled
     */
    private function isItemFilled($item, $template): bool
    {
        if (!$item) {
            return false;
        }

        switch ($template->tipe) {
            case 'checklist':
                return (bool) $item->nilai_checklist;
            case 'upload':
                return !empty($item->nilai_teks);
            case 'numerik':
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
}
