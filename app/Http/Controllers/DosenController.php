<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use App\Models\Kriteria;
use App\Models\Submission;
use App\Models\SubmissionItem;
use Illuminate\Http\Request;
use App\Services\SkorService;
use App\Services\GapAnalysisService;
use App\Services\RadarChartService;
use App\Services\LaporanService;
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
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
    public function showProdiKriteria($prodi_id)
    {
        // Get program studi
        $prodi = ProgramStudi::findOrFail($prodi_id);

        // Check if user has access to this prodi (assign via user_prodi)
        $userProdi = Auth::user()->prodis()->where('program_studi.prodi_id', $prodi_id)->first();
        if (!$userProdi && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Get all level 0 kriteria with their children (level 1)
        $kriterias = Kriteria::where('level', 0)
            ->orderBy('urutan')
            ->with(['children' => function ($query) {
                $query->orderBy('urutan');
            }])
            ->get();

        // Fetch submission statuses for each kriteria (level 1 only)
        $submissions = Submission::where('prodi_id', $prodi_id)
            ->whereHas('kriteria', function ($query) {
                $query->where('level', 1);
            })
            ->get()
            ->keyBy('kriteria_id');

        return view('dosen.kriteria-prodi.index', compact('prodi', 'kriterias', 'submissions'));
    }

    /**
     * Display submission form for a specific kriteria
     */
    public function showSubmission($prodi_id, $kriteria_id)
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

        // Get template items for this kriteria
        $templateItems = $kriteria->templateItems()->orderBy('urutan')->get();

        // Get submission items
        $submissionItems = $submission->items()->get()->keyBy('template_item_id');

        return view('dosen.submission.form', compact('prodi', 'kriteria', 'submission', 'templateItems', 'submissionItems'));
    }

    /**
     * Store submission form data
     */
    public function storeSubmission(Request $request, $prodi_id, $kriteria_id)
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
            $submission->status = 'submitted';
            $submission->submitted_at = now();
        }
        $submission->save();

        // Redirect back with success message
        return redirect()->route('dosen.prodi.kriteria', $prodi_id)
            ->with('success', $action === 'submit' ? 'Submission berhasil dikirim untuk validasi' : 'Draft berhasil disimpan');
    }

    /**
     * Display submission review page (with validator feedback)
     */
    public function showReview($submission_id, SkorService $skorService)
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
     * Display laporan kesiapan prodi (score & gaps)
     */
    public function showProdiLaporan(
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
    public function storeLaporan($prodi_id, LaporanService $laporanService)
    {
        // Check access
        $userProdi = Auth::user()->prodis()
            ->where('program_studi.prodi_id', $prodi_id)
            ->first();

        if (!$userProdi && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $result = $laporanService->generatePDF($prodi_id);

        if ($result['success']) {
            return redirect()->route('dosen.prodi.laporan', $prodi_id)
                ->with('success', 'Laporan PDF berhasil dibuat: ' . $result['filename']);
        } else {
            return redirect()->route('dosen.prodi.laporan', $prodi_id)
                ->with('error', $result['message']);
        }
    }

    /**
     * Reset submission for ditolak status - clear all answers and set to draft
     */
    public function resetSubmission($prodi_id, $submission_id)
    {
        // Find submission
        $submission = Submission::findOrFail($submission_id);

        // Verify submission belongs to the specified prodi
        $kriteria = $submission->kriteria;
        if ($submission->prodi_id != $prodi_id) {
            abort(403, 'Unauthorized');
        }

        // Verify user has access to this prodi
        $userProdi = Auth::user()->prodis()
            ->where('program_studi.prodi_id', $prodi_id)
            ->first();

        if (!$userProdi && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Only allow reset if status is ditolak
        if ($submission->status !== 'ditolak') {
            return redirect()->route('dosen.submission.show', [$prodi_id, $submission->kriteria_id])
                ->with('error', 'Hanya submission dengan status ditolak yang dapat direset.');
        }

        // Delete all submission items
        $submission->items()->delete();

        // Reset submission to draft status
        $submission->update(['status' => 'draft']);

        return redirect()->route('dosen.submission.show', [$prodi_id, $submission->kriteria_id])
            ->with('success', 'Form berhasil direset. Silahkan isi ulang dari awal sesuai dengan catatan validator.');
    }
}
