<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ProgramStudi;
use App\Models\Submission;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard with system-wide metrics
     */
    public function index()
    {
        // Count active prodi
        $totalProdi = ProgramStudi::count();

        // Count total submissions
        $totalSubmissions = Submission::count();

        // Count submissions waiting for validation
        $submissionsWaiting = Submission::where('status', 'submitted')->count();

        // Count total users
        $totalUsers = User::count();

        // Get prodi progress summary
        $prodiProgress = DB::table('program_studi')
            ->leftJoin('submission', 'program_studi.prodi_id', '=', 'submission.prodi_id')
            ->select(
                'program_studi.prodi_id',
                'program_studi.nama',
                'program_studi.kode',
                DB::raw('COUNT(submission.submission_id) as total_submission'),
                DB::raw("SUM(CASE WHEN submission.status = 'diterima' THEN 1 ELSE 0 END) as completed"),
                DB::raw('AVG(submission.skor) as avg_score')
            )
            ->groupBy('program_studi.prodi_id', 'program_studi.nama', 'program_studi.kode')
            ->orderBy('program_studi.nama')
            ->get();

        // Recent submissions (last 10)
        $recentSubmissions = Submission::with(['prodi', 'kriteria', 'user'])
            ->orderBy('submitted_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalProdi',
            'totalSubmissions',
            'submissionsWaiting',
            'totalUsers',
            'prodiProgress',
            'recentSubmissions'
        ));
    }
}
