<?php

namespace App\Services;

use App\Models\Laporan;
use App\Models\ProgramStudi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanService
{
    protected $skorService;
    protected $gapAnalysisService;
    protected $radarChartService;

    public function __construct(
        SkorService $skorService,
        GapAnalysisService $gapAnalysisService,
        RadarChartService $radarChartService
    ) {
        $this->skorService = $skorService;
        $this->gapAnalysisService = $gapAnalysisService;
        $this->radarChartService = $radarChartService;
    }

    /**
     * Generate PDF laporan for a prodi
     * Returns array with path and success status
     */
    public function generatePDF(int $prodi_id): array
    {
        try {
            // Get all data needed for laporan
            $prodi = ProgramStudi::findOrFail($prodi_id);
            $totalScore = $this->skorService->calculateTotalForProdi($prodi_id, 'diterima');
            $scores = $this->skorService->calculateAllForProdi($prodi_id, 'diterima');
            $gaps = $this->gapAnalysisService->analyzeProdi($prodi_id);
            $chartData = $this->radarChartService->generateChartData($prodi_id, 'diterima');
            $overallStatus = $this->radarChartService->getOverallStatus($prodi_id, 'diterima');

            // Prepare view data
            $data = [
                'prodi' => $prodi,
                'total_score' => $totalScore,
                'scores' => $scores,
                'gaps' => $gaps,
                'chart_data' => $chartData,
                'overall_status' => $overallStatus,
                'generated_at' => now()->format('d F Y H:i'),
            ];

            // Render HTML from view
            $html = View::make('dosen.prodi.laporan-pdf', $data)->render();

            // Convert to PDF using DomPDF
            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4')
                ->setOption('isPhpEnabled', true)
                ->setOption('isHtmlOpen', true);

            // Generate filename
            $filename = sprintf(
                'laporan_prodi_%s_%s.pdf',
                $prodi->kode,
                now()->format('YmdHis')
            );

            // Store PDF
            $path = 'laporans/' . $filename;
            Storage::disk('public')->put($path, $pdf->output());

            // Save record in database
            $laporan = Laporan::create([
                'prodi_id' => $prodi_id,
                'generated_by' => Auth::id(),
                'skor_total' => $totalScore,
                'path_pdf' => $path,
            ]);

            return [
                'success' => true,
                'message' => 'Laporan berhasil dibuat',
                'laporan_id' => $laporan->laporan_id,
                'path' => $path,
                'filename' => $filename,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal membuat laporan: ' . $e->getMessage(),
                'error' => $e,
            ];
        }
    }

    /**
     * Get recent laporan for a prodi
     */
    public function getRecentLaporan(int $prodi_id, int $limit = 5)
    {
        return Laporan::where('prodi_id', $prodi_id)
            ->with('user')
            ->orderByDesc('generated_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Delete a laporan file
     */
    public function deleteLaporan(int $laporan_id): array
    {
        try {
            $laporan = Laporan::findOrFail($laporan_id);

            // Delete file
            if (Storage::disk('public')->exists($laporan->path_pdf)) {
                Storage::disk('public')->delete($laporan->path_pdf);
            }

            // Delete record
            $laporan->delete();

            return [
                'success' => true,
                'message' => 'Laporan berhasil dihapus',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus laporan: ' . $e->getMessage(),
            ];
        }
    }
}
