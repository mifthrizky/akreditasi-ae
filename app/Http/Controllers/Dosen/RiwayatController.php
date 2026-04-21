<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    /**
     * Display audit log history for a submission
     * Dosen can view history of their own submissions
     */
    public function show($submission_id)
    {
        $submission = Submission::with(['prodi', 'kriteria', 'user'])
            ->findOrFail($submission_id);

        // Check access: only dosen who created this submission or admin
        if (Auth::id() !== $submission->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke riwayat submission ini');
        }

        // Get audit logs for this submission
        $auditLogs = AuditLogService::getHistory($submission);

        return view('dosen.riwayat.show', compact('submission', 'auditLogs'));
    }
}
