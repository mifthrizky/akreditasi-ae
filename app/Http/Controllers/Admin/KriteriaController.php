<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KriteriaController extends Controller
{
    /**
     * Display a listing of kriteria in hierarchy
     */
    public function index()
    {
        $kriterias = Kriteria::where('level', 0)
            ->orderBy('urutan')
            ->get();

        return view('admin.kriteria.index', compact('kriterias'));
    }

    /**
     * Store a newly created kriteria in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:10|unique:kriteria,kode',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'level' => 'required|in:0,1,2',
            'bobot' => 'required|numeric|min:0|max:100',
            'urutan' => 'required|integer|min:1',
            'parent_id' => 'nullable|exists:kriteria,kriteria_id',
        ], [
            'kode.required' => 'Kode harus diisi',
            'kode.unique' => 'Kode sudah terdaftar',
            'nama.required' => 'Nama harus diisi',
            'level.required' => 'Level harus dipilih',
            'level.in' => 'Level hanya boleh 0, 1, atau 2',
            'bobot.required' => 'Bobot harus diisi',
            'bobot.numeric' => 'Bobot harus berupa angka',
            'urutan.required' => 'Urutan harus diisi',
            'urutan.integer' => 'Urutan harus berupa angka bulat',
            'parent_id.exists' => 'Parent kriteria tidak ditemukan',
        ]);

        try {
            Kriteria::create($validated);
            return redirect()->route('admin.kriteria.index')
                ->with('success', 'Kriteria berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan kriteria: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified kriteria in database
     */
    public function update(Request $request, $id)
    {
        $kriteria = Kriteria::findOrFail($id);

        $validated = $request->validate([
            'kode' => 'required|string|max:10|unique:kriteria,kode,' . $id . ',kriteria_id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'level' => 'required|in:0,1,2',
            'bobot' => 'required|numeric|min:0|max:100',
            'urutan' => 'required|integer|min:1',
            'parent_id' => 'nullable|exists:kriteria,kriteria_id',
        ], [
            'kode.required' => 'Kode harus diisi',
            'kode.unique' => 'Kode sudah terdaftar',
            'nama.required' => 'Nama harus diisi',
            'level.required' => 'Level harus dipilih',
            'level.in' => 'Level hanya boleh 0, 1, atau 2',
            'bobot.required' => 'Bobot harus diisi',
            'bobot.numeric' => 'Bobot harus berupa angka',
            'urutan.required' => 'Urutan harus diisi',
            'urutan.integer' => 'Urutan harus berupa angka bulat',
            'parent_id.exists' => 'Parent kriteria tidak ditemukan',
        ]);

        try {
            $kriteria->update($validated);
            return redirect()->route('admin.kriteria.index')
                ->with('success', 'Kriteria berhasil diperbarui');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui kriteria: ' . $e->getMessage());
        }
    }

    /**
     * Delete the specified kriteria from database
     */
    public function destroy($id)
    {
        try {
            $kriteria = Kriteria::findOrFail($id);

            // Check if kriteria has children (sub-kriteria)
            if (in_array($kriteria->level, [0, 1]) && $kriteria->children()->count() > 0) {
                return back()
                    ->with('error', 'Tidak dapat menghapus kriteria yang memiliki sub-kriteria');
            }

            $kriteria->delete();
            return redirect()->route('admin.kriteria.index')
                ->with('success', 'Kriteria berhasil dihapus');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menghapus kriteria: ' . $e->getMessage());
        }
    }

    /**
     * Show template configuration for a sub-kriteria
     */
    public function showTemplate($id)
    {
        $kriteria = Kriteria::where('level', 2)->findOrFail($id);
        $templateItems = $kriteria->templateItems()->orderBy('urutan')->get();

        return view('admin.kriteria.template', compact('kriteria', 'templateItems'));
    }
}
