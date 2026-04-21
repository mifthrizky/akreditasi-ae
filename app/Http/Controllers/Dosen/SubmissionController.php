<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\Kriteria;
use App\Models\Submission;
use App\Models\SubmissionItem;
use Illuminate\Http\Request;
use App\Services\SkorService;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    /**
     * Display list of program studi assigned to dosen
     */
    public function indexProdi()
    {
        $prodis = Auth::user()->prodis()->get();
        return view('dosen.prodi.index', compact('prodis'));
    }

    /**
     * Display kriteria list for dosen's assigned prodi
     */
    public function kriteriIndex($prodi_id)
    {
        // Get program studi
        $prodi = ProgramStudi::findOrFail($prodi_id);

        // Check if user has access to this prodi
        $userProdi = Auth::user()->prodis()->where('program_studi.prodi_id', $prodi_id)->first();
        if (!$userProdi && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Get all level 0 kriteria with their children (level 1) and grandchildren (level 2)
        $kriterias = Kriteria::where('level', 0)
            ->orderBy('urutan')
            ->with(['children' => function ($query) {
                $query->orderBy('urutan')->with(['children' => function ($q) {
                    $q->orderBy('urutan');
                }]);
            }])
            ->get();

        // Fetch submission statuses for each kriteria (level 2 only)
        $submissions = Submission::where('prodi_id', $prodi_id)
            ->whereHas('kriteria', function ($query) {
                $query->where('level', 2);
            })
            ->get()
            ->keyBy('kriteria_id');

        return view('dosen.kriteria-prodi.index', compact('prodi', 'kriterias', 'submissions'));
    }

    /**
     * Display submission form for a specific kriteria
     */
    public function show($prodi_id, $kriteria_id)
    {
        // Get program studi
        $prodi = ProgramStudi::findOrFail($prodi_id);

        // Check access
        $userProdi = Auth::user()->prodis()->where('program_studi.prodi_id', $prodi_id)->first();
        if (!$userProdi && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Get kriteria
        $kriteria = Kriteria::findOrFail($kriteria_id);

        // Get or create submission
        $submission = Submission::firstOrCreate(
            ['prodi_id' => $prodi_id, 'kriteria_id' => $kriteria_id],
            [
                'user_id' => Auth::id(),
                'status' => 'draft',
                'submitted_at' => now(),
                'updated_at' => now()
            ]
        );

        // Check edit lock: cannot edit if status = 'diterima' (approved)
        if (!$submission->canEdit()) {
            abort(403, 'Submission ini sudah disetujui dan tidak dapat diubah. Hubungi admin untuk mereset.');
        }

        // Get template items for this kriteria
        $templateItems = $kriteria->templateItems()->orderBy('urutan')->get();

        // Get submission items
        $submissionItems = $submission->items()->get()->keyBy('template_item_id');

        return view('dosen.submission.form', compact('prodi', 'kriteria', 'submission', 'templateItems', 'submissionItems'));
    }

    /**
     * Store submission form data
     */
    public function store(Request $request, $prodi_id, $kriteria_id)
    {
        // Get program studi
        $prodi = ProgramStudi::findOrFail($prodi_id);

        // Check access
        $userProdi = Auth::user()->prodis()->where('program_studi.prodi_id', $prodi_id)->first();
        if (!$userProdi && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Get kriteria and submission
        $kriteria = Kriteria::findOrFail($kriteria_id);
        $submission = Submission::where('prodi_id', $prodi_id)
            ->where('kriteria_id', $kriteria_id)
            ->firstOrFail();

        // Check edit lock
        if (!$submission->canEdit()) {
            abort(403, 'Submission ini sudah disetujui dan tidak dapat diubah.');
        }

        // Get template items
        $templateItems = $kriteria->templateItems()->get();

        // Process each template item
        foreach ($templateItems as $template) {
            $inputName = "template_" . $template->template_id;

            $submissionItem = SubmissionItem::firstOrCreate(
                ['submission_id' => $submission->submission_id, 'template_item_id' => $template->template_id],
                []
            );

            // Handle based on tipe
            if ($template->tipe === 'checklist') {
                $submissionItem->nilai_checklist = $request->has($inputName);
            } elseif ($template->tipe === 'upload') {
                if ($request->hasFile($inputName)) {
                    $file = $request->file($inputName);
                    $path = $file->store('submissions', 'public');
                    $submissionItem->nilai_teks = $path;
                }
            } elseif ($template->tipe === 'numerik') {
                $submissionItem->nilai_numerik = $request->input($inputName);
            } elseif ($template->tipe === 'narasi') {
                $submissionItem->nilai_teks = $request->input($inputName);
            }

            $submissionItem->save();
        }

        // Update submission status based on action
        $action = $request->input('action');
        if ($action === 'submit') {
            // Calculate current score before submission
            $skorService = app(SkorService::class);
            $currentScore = $skorService->calculate($submission);

            // PRD requirement: score must be ≥50% to submit
            if ($currentScore < 50) {
                return redirect()->back()
                    ->with('error', "Skor submission ({$currentScore}%) harus minimal 50% untuk submit. Lengkapi terlebih dahulu.");
            }

            // Validate state transition: draft → submitted
            $transition = $submission->canTransitionTo('submitted', Auth::user()->role);
            if (!$transition['valid']) {
                return redirect()->back()->with('error', $transition['message']);
            }

            $submission->status = 'submitted';
            $submission->submitted_at = now();
            $submission->skor = $currentScore;

            // Save and log
            $submission->save();
            AuditLogService::logSubmit($submission, $currentScore);
        } else {
            $submission->save();
        }

        // Redirect back with success message
        return redirect()->route('dosen.submission.kriteria-index', $prodi_id)
            ->with('success', $action === 'submit' ? 'Submission berhasil dikirim untuk validasi' : 'Draft berhasil disimpan');
    }

    /**
     * Display submission review page (with validator feedback)
     */
    public function review($submission_id, SkorService $skorService)
    {
        $submission = Submission::with('kriteria', 'items.templateItem', 'validasi.user')
            ->findOrFail($submission_id);

        // Check access
        $userProdi = Auth::user()->prodis()
            ->where('program_studi.prodi_id', $submission->prodi_id)
            ->first();

        if (!$userProdi && Auth::user()->role !== 'admin' && Auth::id() !== $submission->user_id) {
            abort(403, 'Unauthorized');
        }

        $prodi = $submission->prodi;
        $kriteria = $submission->kriteria;
        $templateItems = $kriteria->templateItems()->orderBy('urutan')->get();
        $submissionItems = $submission->items()->get()->keyBy('template_item_id');
        $skor = $skorService->calculate($submission);

        return view('dosen.submission.review', compact(
            'prodi',
            'kriteria',
            'submission',
            'templateItems',
            'submissionItems',
            'skor'
        ));
    }

    /**
     * Reset submission (only if status = 'ditolak')
     */
    public function reset($prodi_id, $submission_id)
    {
        $prodi = ProgramStudi::findOrFail($prodi_id);
        $submission = Submission::findOrFail($submission_id);

        // Check access
        $userProdi = Auth::user()->prodis()->where('program_studi.prodi_id', $prodi_id)->first();
        if (!$userProdi && Auth::user()->role !== 'admin' && Auth::id() !== $submission->user_id) {
            abort(403, 'Unauthorized');
        }

        // Only allow reset if status = 'ditolak' or 'revisi'
        if (!in_array($submission->status, ['ditolak', 'revisi'])) {
            return redirect()->back()
                ->with('error', 'Submission hanya dapat direset jika status ditolak atau diminta revisi');
        }

        // Delete all submission items
        $submission->items()->delete();

        // Reset to draft
        $submission->status = 'draft';
        $submission->submitted_at = now();
        $submission->skor = null;
        $submission->save();

        return redirect()->route('dosen.submission.kriteria-index', $prodi_id)
            ->with('success', 'Submission berhasil direset ke draft. Silakan perbaiki dan submit kembali');
    }
}
