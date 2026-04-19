<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\ProgramStudi;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidatorController extends Controller
{
    /**
     * Display antrian submission yang menunggu review
     * Filter: status='submitted', by prodi, by kriteria
     */
    public function indexAntrian(Request $request)
    {
        // Get filter parameters
        $prodi_id = $request->input('prodi_id');
        $kriteria_id = $request->input('kriteria_id');

        // Build query
        $query = Submission::where('status', 'submitted')
            ->with(['prodi', 'kriteria', 'user', 'items.templateItem'])
            ->orderBy('submitted_at', 'desc');

        // Filter by prodi
        if ($prodi_id) {
            $query->where('prodi_id', $prodi_id);
        }

        // Filter by kriteria
        if ($kriteria_id) {
            $query->where('kriteria_id', $kriteria_id);
        }

        $submissions = $query->paginate(10);

        // Get all prodi for filter dropdown
        $prodis = ProgramStudi::orderBy('nama')->get();

        // Get all kriteria (level 1) for filter dropdown
        $kriterias = Kriteria::where('level', 1)
            ->orderBy('kode')
            ->get();

        return view('validator.antrian-review.index', compact(
            'submissions',
            'prodis',
            'kriterias',
            'prodi_id',
            'kriteria_id'
        ));
    }

    /**
     * Show detail submission for review
     */
    public function showReview($submission_id)
    {
        $submission = Submission::with(['prodi', 'kriteria', 'user', 'items.templateItem', 'validasi'])
            ->findOrFail($submission_id);

        // Check if already validated
        if ($submission->status !== 'submitted') {
            abort(403, 'Submission ini sudah di-review sebelumnya');
        }

        return view('validator.antrian-review.detail', compact('submission'));
    }

    /**
     * Store validasi (approve/revisi/reject) + komentar
     */
    public function storeValidasi(Request $request, $submission_id)
    {
        $submission = Submission::findOrFail($submission_id);

        // Check if already validated
        if ($submission->status !== 'submitted') {
            return redirect()->route('validator.antrian')
                ->with('error', 'Submission ini sudah di-review');
        }

        // Validate input
        $validated = $request->validate([
            'status' => 'required|in:disetujui,revisi,ditolak',
            'komentar' => 'required_if:status,revisi,ditolak|nullable|string|max:5000',
        ], [
            'status.required' => 'Status validasi wajib dipilih',
            'status.in' => 'Status tidak valid',
            'komentar.required_if' => 'Komentar wajib diisi untuk status revisi/ditolak',
            'komentar.max' => 'Komentar maksimal 5000 karakter',
        ]);

        // Map validasi status to submission status
        $submissionStatus = match ($validated['status']) {
            'disetujui' => 'diterima',
            'revisi' => 'revisi',
            'ditolak' => 'ditolak',
        };

        // Create/update validasi record
        $submission->validasi()->updateOrCreate(
            ['submission_id' => $submission_id],
            [
                'validator_id' => Auth::id(),
                'status' => $validated['status'],
                'komentar' => $validated['komentar'] ?? null,
                'validated_at' => now(),
            ]
        );

        // Update submission status
        $submission->update(['status' => $submissionStatus]);

        $statusLabel = match ($validated['status']) {
            'disetujui' => 'Disetujui',
            'revisi' => 'Dikembalikan untuk revisi',
            'ditolak' => 'Ditolak',
        };

        return redirect()->route('validator.antrian')
            ->with('success', "Submission {$statusLabel}");
    }
}
