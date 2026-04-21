<?php

namespace App\Http\Controllers\Validator;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display validator dashboard with queue summary
     */
    public function index()
    {
        // Count submissions by status
        $waitingReview = Submission::where('status', 'submitted')->count();
        $completedToday = Submission::where('status', 'diterima')
            ->whereDate('updated_at', today())
            ->count();

        $totalApproved = Submission::where('status', 'diterima')->count();
        $totalReturned = Submission::whereIn('status', ['revisi', 'ditolak'])->count();

        // Get recent validations by current validator
        $recentValidations = DB::table('validasi')
            ->join('submission', 'validasi.submission_id', '=', 'submission.submission_id')
            ->join('program_studi', 'submission.prodi_id', '=', 'program_studi.prodi_id')
            ->join('kriteria', 'submission.kriteria_id', '=', 'kriteria.kriteria_id')
            ->select(
                'submission.submission_id',
                'program_studi.nama as prodi_nama',
                'kriteria.nama as kriteria_nama',
                'validasi.status',
                'validasi.validated_at'
            )
            ->where('validasi.validator_id', Auth::id())
            ->orderBy('validasi.validated_at', 'desc')
            ->limit(5)
            ->get();

        return view('validator.dashboard', compact(
            'waitingReview',
            'completedToday',
            'totalApproved',
            'totalReturned',
            'recentValidations'
        ));
    }
}
