<?php

namespace App\Http\Controllers\Validator;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    /**
     * Display validator's validation history
     * Shows all submissions reviewed by this validator, with dates and actions
     */
    public function index(Request $request)
    {
        $status = $request->input('status'); // approved, rejected, revision
        $prodiId = $request->input('prodi_id'); // added prodi filter
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        // Build query: get audit logs where validator is the user (via Auth::id())
        $query = AuditLog::where('user_id', Auth::id())
            ->with(['submission.prodi', 'submission.kriteria', 'submission.user'])
            ->whereIn('action', ['approved', 'rejected', 'revision']);

        // Filter by action/status
        if ($status) {
            $actionMap = ['approved' => 'approved', 'rejected' => 'rejected', 'revision' => 'revision'];
            if (isset($actionMap[$status])) {
                $query->where('action', $actionMap[$status]);
            }
        }

        // Filter by prodi via submission relation
        if ($prodiId) {
            $query->whereHas('submission', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            });
        }

        // Filter by date range
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(15);
        $prodis = \App\Models\ProgramStudi::orderBy('nama')->get();

        return view('validator.riwayat.index', compact('auditLogs', 'status', 'prodiId', 'dateFrom', 'dateTo', 'prodis'));
    }

    /**
     * Show detail of a specific validation action
     */
    public function show($auditLogId)
    {
        $auditLog = AuditLog::with(['submission.prodi', 'submission.kriteria', 'submission.items.templateItem', 'user'])
            ->findOrFail($auditLogId);

        // Check access: only validator who performed this action or admin
        if (Auth::id() !== $auditLog->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke riwayat ini');
        }

        $submission = $auditLog->submission;

        // Used by riwayat index modal (AJAX) so we don't need a dedicated page.
        // Keep non-modal view for direct navigation/debugging.
        if (request()->boolean('modal') || request()->ajax()) {
            return view('validator.riwayat._detail', compact('auditLog', 'submission'));
        }

        return view('validator.riwayat.show', compact('auditLog', 'submission'));
    }
}
