<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Services\ProdiDataCleanupService;
use Illuminate\Http\Request;

class ProdiDataCleanupController extends Controller
{
    protected $cleanupService;
    
    public function __construct(ProdiDataCleanupService $cleanupService)
    {
        $this->cleanupService = $cleanupService;
    }
    
    /**
     * Check if user is super_admin
     */
    private function checkSuperAdmin()
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Hanya Super Admin yang dapat mengakses fitur ini');
        }
    }
    
    /**
     * Show prodi selection page
     */
    public function index()
    {
        $this->checkSuperAdmin();
        
        $prodis = ProgramStudi::orderBy('nama')->get();
        
        return view('admin.prodi-data-cleanup.index', compact('prodis'));
    }
    
    /**
     * AJAX: Get preview of data to be deleted
     */
    public function preview(Request $request)
    {
        $this->checkSuperAdmin();
        $request->validate([
            'prodi_id' => 'required|exists:program_studi,prodi_id',
        ]);
        
        try {
            $summary = $this->cleanupService->getDataSummary($request->prodi_id);
            
            return response()->json([
                'success' => true,
                'data' => $summary,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Execute deletion
     */
    public function destroy(Request $request, $prodiId)
    {
        $this->checkSuperAdmin();
        
        $request->validate([
            'confirmation_text' => 'required|string',
        ]);
        
        try {
            $prodi = ProgramStudi::findOrFail($prodiId);
            
            // Verify confirmation text matches prodi name
            if (strtolower(trim($request->confirmation_text)) !== strtolower($prodi->nama)) {
                return back()->with('error', 'Nama program studi tidak sesuai. Penghapusan dibatalkan.');
            }
            
            // Execute deletion
            $result = $this->cleanupService->deleteProdiData($prodiId);
            
            if ($result['success']) {
                $message = $result['message'] . '. Backup: ' . $result['backup_path'];
                
                return redirect()->route('admin.prodi-data-cleanup.index')
                    ->with('success', $message);
            } else {
                return back()->with('error', $result['message']);
            }
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
