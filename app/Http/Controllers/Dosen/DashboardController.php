<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\Submission;
use App\Services\SkorService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display dosen dashboard with assigned prodi list
     */
    public function index()
    {
        // Get all prodi assigned to dosen
        $prodis = Auth::user()->prodis()->get();

        // Auto-redirect removed per user request to always see the dashboard

        // Build prodi progress info
        $skorService = app(SkorService::class);

        $prodiStats = $prodis->map(function ($prodi) use ($skorService) {
            $submissions = Submission::where('prodi_id', $prodi->prodi_id)->get();
            $completed = $submissions->where('status', 'diterima')->count();
            $total = $submissions->count();
            $avgScore = $submissions->where('status', 'diterima')->avg('skor') ?? 0;

            return [
                'prodi' => $prodi,
                'total_submissions' => $total,
                'completed_submissions' => $completed,
                'progress_percentage' => $total > 0 ? round(($completed / $total) * 100) : 0,
                'avg_score' => round($avgScore, 2),
            ];
        });

        return view('dosen.dashboard', compact('prodis', 'prodiStats'));
    }
}
