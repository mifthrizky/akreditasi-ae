<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\Kriteria;
use App\Services\SkorService;
use App\Services\GapAnalysisService;
use App\Services\RadarChartService;
use App\Services\LaporanService;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Display laporan kesiapan prodi (score & gaps)
     */
    public function show(
        $prodi_id,
        SkorService $skorService,
        GapAnalysisService $gapAnalysisService,
        RadarChartService $radarChartService,
        LaporanService $laporanService
    ) {
        $prodi = ProgramStudi::findOrFail($prodi_id);

        // Check access
        $userProdi = Auth::user()->prodis()
            ->where('program_studi.prodi_id', $prodi_id)
            ->first();

        if (!$userProdi && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Get kriteria
        $kriterias = Kriteria::where('level', 0)
            ->orderBy('urutan')
            ->with(['children' => function ($query) {
                $query->orderBy('urutan');
            }])
            ->get();

        // Calculate scores
        $scores = $skorService->calculateAllForProdi($prodi_id, 'diterima');
        $totalScore = $skorService->calculateTotalForProdi($prodi_id, 'diterima');

        // Get gaps
        $gaps = $gapAnalysisService->analyzeProdi($prodi_id);
        $gapsByParent = $gapAnalysisService->analyzeProdiByParent($prodi_id);

        // Get chart data
        $chartData = $radarChartService->generateChartData($prodi_id, 'diterima');
        $chartDataJson = $radarChartService->generateChartDataJson($prodi_id, 'diterima');
        $overallStatus = $radarChartService->getOverallStatus($prodi_id, 'diterima');

        // Get recent laporans
        $recentLaporans = $laporanService->getRecentLaporan($prodi_id);

        return view('dosen.prodi.laporan', compact(
            'prodi',
            'kriterias',
            'scores',
            'totalScore',
            'gaps',
            'gapsByParent',
            'chartData',
            'chartDataJson',
            'overallStatus',
            'recentLaporans'
        ));
    }

    /**
     * Generate and store laporan PDF
     */
    public function store($prodi_id, LaporanService $laporanService)
    {
        $prodi = ProgramStudi::findOrFail($prodi_id);

        // Check access
        $userProdi = Auth::user()->prodis()
            ->where('program_studi.prodi_id', $prodi_id)
            ->first();

        if (!$userProdi && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Generate laporan PDF
        $result = $laporanService->generateLaporanPDF($prodi_id, Auth::id());

        if (!$result['success']) {
            return redirect()->back()
                ->with('error', $result['message'] ?? 'Gagal membuat laporan');
        }

        return redirect()->back()
            ->with('success', 'Laporan berhasil dibuat');
    }
}
