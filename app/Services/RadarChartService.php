<?php

namespace App\Services;

use App\Models\Kriteria;

class RadarChartService
{
    protected $skorService;

    const IABEE_MINIMUM = 70; // 70% minimum standard

    public function __construct(SkorService $skorService)
    {
        $this->skorService = $skorService;
    }

    /**
     * Generate Chart.js radar chart data for a prodi
     * Returns JSON-ready array with labels and datasets for actual vs minimum
     */
    public function generateChartData(int $prodi_id, string $status = 'diterima'): array
    {
        // Get all level-0 kriteria (parents)
        $parents = Kriteria::where('level', 0)
            ->orderBy('urutan')
            ->get();

        // Get aggregated scores (parent level)
        $scores = $this->skorService->aggregateToParent($prodi_id, $status);

        // Build labels and actual scores
        $labels = [];
        $actualData = [];

        foreach ($parents as $parent) {
            $labels[] = $parent->nama;
            // Convert to percentage (0-100)
            $score = isset($scores[$parent->kriteria_id])
                ? $scores[$parent->kriteria_id]
                : 0;
            $actualData[] = round($score, 2);
        }

        // Standard/minimum line (all same value)
        $minimumData = array_fill(0, count($labels), self::IABEE_MINIMUM);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Skor Aktual (%)',
                    'data' => $actualData,
                    'borderColor' => 'rgb(59, 130, 246)',    // Blue
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderWidth' => 2,
                    'pointBackgroundColor' => 'rgb(59, 130, 246)',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 5,
                    'pointHoverRadius' => 7,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Standar IABEE (' . self::IABEE_MINIMUM . '%)',
                    'data' => $minimumData,
                    'borderColor' => 'rgb(239, 68, 68)',     // Red
                    'backgroundColor' => 'rgba(239, 68, 68, 0.05)',
                    'borderWidth' => 2,
                    'borderDash' => [5, 5],
                    'pointBackgroundColor' => 'rgb(239, 68, 68)',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                    'tension' => 0.3,
                ],
            ],
        ];
    }

    /**
     * Generate as JSON string (for inline in HTML)
     */
    public function generateChartDataJson(int $prodi_id, string $status = 'diterima'): string
    {
        $data = $this->generateChartData($prodi_id, $status);
        return json_encode($data);
    }

    /**
     * Generate Chart.js options/config
     */
    public function generateChartOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => true,
            'scales' => [
                'r' => [
                    'min' => 0,
                    'max' => 100,
                    'ticks' => [
                        'stepSize' => 20,
                        'callback' => 'function(value) { return value + "%"; }',
                    ],
                    'grid' => [
                        'color' => 'rgba(229, 231, 235, 0.5)',
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'font' => [
                            'size' => 12,
                        ],
                    ],
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return context.dataset.label + ": " + context.parsed.r + "%"; }',
                    ],
                ],
            ],
        ];
    }

    /**
     * Get Chart.js options as JSON string
     */
    public function generateChartOptionsJson(): string
    {
        return json_encode($this->generateChartOptions());
    }

    /**
     * Get overall status based on total score vs IABEE minimum
     */
    public function getOverallStatus(int $prodi_id, string $status = 'diterima'): array
    {
        $totalScore = $this->skorService->calculateTotalForProdi($prodi_id, $status);

        return [
            'total_score' => round($totalScore, 2),
            'total_score_percent' => round($totalScore, 2),
            'minimum_required' => self::IABEE_MINIMUM,
            'status' => $totalScore >= self::IABEE_MINIMUM ? 'passed' : 'failed',
            'color' => $this->skorService->getStatusColor($totalScore / 100),
            'label' => $this->skorService->getStatusLabel($totalScore / 100),
        ];
    }
}
