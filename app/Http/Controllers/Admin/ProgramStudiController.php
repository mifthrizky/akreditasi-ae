<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ProgramStudi;
use App\Http\Controllers\Controller;

class ProgramStudiController extends Controller
{
    /**
     * Display a listing of all program studies
     */
    public function index()
    {
        $programs = ProgramStudi::orderBy('nama', 'asc')->paginate(10);
        return view('admin.program-studi.index', compact('programs'));
    }

    /**
     * Show the form for creating a new program study
     */
    public function create()
    {
        return view('admin.program-studi.create');
    }

    /**
     * Store a newly created program study in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:program_studi,kode',
            'nama' => 'required|string|max:255|unique:program_studi,nama',
            'jurusan' => 'required|string|max:255',
        ], [
            'kode.required' => 'Kode program studi harus diisi',
            'kode.unique' => 'Kode program studi sudah terdaftar',
            'nama.required' => 'Nama program studi harus diisi',
            'nama.unique' => 'Nama program studi sudah terdaftar',
            'jurusan.required' => 'Jurusan harus diisi',
        ]);

        try {
            ProgramStudi::create($validated);
            return redirect()->route('admin.program-studi.index')
                ->with('success', 'Program studi berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan program studi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified program study
     */
    public function show($id)
    {
        $program = ProgramStudi::findOrFail($id);
        return view('admin.program-studi.show', compact('program'));
    }

    /**
     * Show the form for editing the specified program study
     */
    public function edit($id)
    {
        $program = ProgramStudi::findOrFail($id);
        return view('admin.program-studi.edit', compact('program'));
    }

    /**
     * Update the specified program study in database
     */
    public function update(Request $request, $id)
    {
        $program = ProgramStudi::findOrFail($id);

        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:program_studi,kode,' . $id . ',prodi_id',
            'nama' => 'required|string|max:255|unique:program_studi,nama,' . $id . ',prodi_id',
            'jurusan' => 'required|string|max:255',
        ], [
            'kode.required' => 'Kode program studi harus diisi',
            'kode.unique' => 'Kode program studi sudah terdaftar',
            'nama.required' => 'Nama program studi harus diisi',
            'nama.unique' => 'Nama program studi sudah terdaftar',
            'jurusan.required' => 'Jurusan harus diisi',
        ]);

        try {
            $program->update($validated);
            return redirect()->route('admin.program-studi.index')
                ->with('success', 'Program studi berhasil diperbarui');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui program studi: ' . $e->getMessage());
        }
    }

    /**
     * Delete the specified program study from database
     */
    public function destroy($id)
    {
        try {
            $program = ProgramStudi::findOrFail($id);
            $program->delete();
            return redirect()->route('admin.program-studi.index')
                ->with('success', 'Program studi berhasil dihapus');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menghapus program studi: ' . $e->getMessage());
        }
    }
}
