<?php

namespace App\Http\Controllers;

use App\Models\TemplateItem;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class TemplateItemController extends Controller
{
    /**
     * Store a newly created template item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kriteria_id' => 'required|exists:kriteria,kriteria_id',
            'tipe' => 'required|in:checklist,upload,numerik,narasi,select',
            'label' => 'required|string|max:255',
            'hint' => 'nullable|string|max:255',
            'wajib' => 'boolean',
            'bobot' => 'required|numeric|min:0|max:100',
            'nilai_min_numerik' => 'nullable|numeric|min:0',
            'urutan' => 'required|integer|min:1',
        ], [
            'kriteria_id.required' => 'Kriteria harus dipilih',
            'kriteria_id.exists' => 'Kriteria tidak ditemukan',
            'tipe.required' => 'Tipe harus dipilih',
            'tipe.in' => 'Tipe tidak valid',
            'label.required' => 'Label harus diisi',
            'bobot.required' => 'Bobot harus diisi',
            'bobot.numeric' => 'Bobot harus berupa angka',
            'urutan.required' => 'Urutan harus diisi',
            'urutan.integer' => 'Urutan harus berupa angka bulat',
        ]);

        // Verify that kriteria is level 1
        $kriteria = Kriteria::findOrFail($validated['kriteria_id']);
        if ($kriteria->level != 1) {
            return back()
                ->with('error', 'Template hanya dapat ditambahkan untuk sub-kriteria (level 1)');
        }

        // Validate bobot doesn't exceed remaining
        $existingBobot = TemplateItem::where('kriteria_id', $validated['kriteria_id'])
            ->sum('bobot');
        $remainingBobot = $kriteria->bobot - $existingBobot;

        if ($validated['bobot'] > $remainingBobot) {
            return back()
                ->withInput()
                ->with('error', "Bobot template item tidak boleh melebihi sisa bobot (Sisa: {$remainingBobot}%, Diminta: {$validated['bobot']}%)");
        }

        $validated['wajib'] = $request->has('wajib') ? true : false;

        try {
            TemplateItem::create($validated);
            return redirect()->route('kriteria.template', $kriteria->kriteria_id)
                ->with('success', 'Template item berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan template item: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified template item
     */
    public function update(Request $request, $id)
    {
        $templateItem = TemplateItem::findOrFail($id);

        $validated = $request->validate([
            'tipe' => 'required|in:checklist,upload,numerik,narasi,select',
            'label' => 'required|string|max:255',
            'hint' => 'nullable|string|max:255',
            'wajib' => 'boolean',
            'bobot' => 'required|numeric|min:0|max:100',
            'nilai_min_numerik' => 'nullable|numeric|min:0',
            'urutan' => 'required|integer|min:1',
        ], [
            'tipe.required' => 'Tipe harus dipilih',
            'tipe.in' => 'Tipe tidak valid',
            'label.required' => 'Label harus diisi',
            'bobot.required' => 'Bobot harus diisi',
            'bobot.numeric' => 'Bobot harus berupa angka',
            'urutan.required' => 'Urutan harus diisi',
            'urutan.integer' => 'Urutan harus berupa angka bulat',
        ]);

        // Validate bobot doesn't exceed remaining
        $kriteria = Kriteria::findOrFail($templateItem->kriteria_id);
        $existingBobotOthers = TemplateItem::where('kriteria_id', $templateItem->kriteria_id)
            ->where('template_id', '!=', $id)
            ->sum('bobot');
        $totalAfterUpdate = $existingBobotOthers + $validated['bobot'];

        if ($totalAfterUpdate > $kriteria->bobot) {
            $remainingAfterUpdate = $kriteria->bobot - $existingBobotOthers;
            return back()
                ->withInput()
                ->with('error', "Bobot template item tidak boleh melebihi sisa bobot (Sisa: {$remainingAfterUpdate}%, Diminta: {$validated['bobot']}%)");
        }

        $validated['wajib'] = $request->has('wajib') ? true : false;

        try {
            $templateItem->update($validated);
            return redirect()->route('kriteria.template', $templateItem->kriteria_id)
                ->with('success', 'Template item berhasil diperbarui');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui template item: ' . $e->getMessage());
        }
    }

    /**
     * Delete the specified template item
     */
    public function destroy($id)
    {
        try {
            $templateItem = TemplateItem::findOrFail($id);
            $kriteriaId = $templateItem->kriteria_id;
            $templateItem->delete();

            return redirect()->route('kriteria.template', $kriteriaId)
                ->with('success', 'Template item berhasil dihapus');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menghapus template item: ' . $e->getMessage());
        }
    }
}
