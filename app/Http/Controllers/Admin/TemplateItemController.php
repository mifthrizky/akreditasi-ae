<?php

namespace App\Http\Controllers\Admin;

use App\Models\TemplateItem;
use App\Models\Kriteria;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TemplateItemController extends Controller
{
    /**
     * Store a newly created template item
     * Validates that total bobot = 100% (excluding narasi items)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kriteria_id' => 'required|exists:kriteria,kriteria_id',
            'tipe' => 'required|in:checklist,upload,numerik,narasi',
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

        // For non-narasi items, validate that total bobot will equal 100
        if ($validated['tipe'] !== 'narasi') {
            // Get sum of existing non-narasi items
            $existingBobotNonNarasi = TemplateItem::where('kriteria_id', $validated['kriteria_id'])
                ->where('tipe', '!=', 'narasi')
                ->sum('bobot');

            // Calculate total after adding this item
            $totalBobotAfterAdd = $existingBobotNonNarasi + $validated['bobot'];

            if ($totalBobotAfterAdd > 100) {
                $remainingBobot = 100 - $existingBobotNonNarasi;
                return back()
                    ->withInput()
                    ->with('error', "Total bobot item template harus = 100%. Saat ini jika ditambah akan menjadi {$totalBobotAfterAdd}% (Sisa tersedia: {$remainingBobot}%)");
            }
        }

        // Narasi items must have bobot = 0
        if ($validated['tipe'] === 'narasi' && $validated['bobot'] != 0) {
            return back()
                ->withInput()
                ->with('error', 'Narasi items harus memiliki bobot = 0 (tidak berkontribusi pada skor)');
        }

        $validated['wajib'] = $request->has('wajib') ? true : false;

        try {
            TemplateItem::create($validated);

            // Check if total is now 100 and provide info
            $totalBobotNonNarasi = TemplateItem::where('kriteria_id', $validated['kriteria_id'])
                ->where('tipe', '!=', 'narasi')
                ->sum('bobot');

            $message = 'Template item berhasil ditambahkan';
            if ($totalBobotNonNarasi == 100) {
                $message .= ' (Total bobot sekarang 100%)';
            } elseif ($totalBobotNonNarasi < 100) {
                $message .= " (Total bobot: {$totalBobotNonNarasi}%, masih kurang " . (100 - $totalBobotNonNarasi) . '%))';
            }

            return redirect()->route('admin.kriteria.template', $kriteria->kriteria_id)
                ->with('success', $message);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan template item: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified template item
     * Validates that total bobot = 100%
     */
    public function update(Request $request, $id)
    {
        $templateItem = TemplateItem::findOrFail($id);

        $validated = $request->validate([
            'tipe' => 'required|in:checklist,upload,numerik,narasi',
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

        $kriteria = Kriteria::findOrFail($templateItem->kriteria_id);

        // For non-narasi items, validate total bobot
        if ($validated['tipe'] !== 'narasi') {
            // Get sum of existing non-narasi items (excluding this one)
            $existingBobotNonNarasi = TemplateItem::where('kriteria_id', $templateItem->kriteria_id)
                ->where('tipe', '!=', 'narasi')
                ->where('template_id', '!=', $id)
                ->sum('bobot');

            $totalBobotAfterUpdate = $existingBobotNonNarasi + $validated['bobot'];

            if ($totalBobotAfterUpdate > 100) {
                $remainingBobot = 100 - $existingBobotNonNarasi;
                return back()
                    ->withInput()
                    ->with('error', "Total bobot item template harus = 100%. Saat ini jika diperbarui akan menjadi {$totalBobotAfterUpdate}% (Sisa tersedia: {$remainingBobot}%)");
            }
        }

        // Narasi items must have bobot = 0
        if ($validated['tipe'] === 'narasi' && $validated['bobot'] != 0) {
            return back()
                ->withInput()
                ->with('error', 'Narasi items harus memiliki bobot = 0 (tidak berkontribusi pada skor)');
        }

        $validated['wajib'] = $request->has('wajib') ? true : false;

        try {
            $templateItem->update($validated);

            // Check and provide info
            $totalBobotNonNarasi = TemplateItem::where('kriteria_id', $templateItem->kriteria_id)
                ->where('tipe', '!=', 'narasi')
                ->sum('bobot');

            $message = 'Template item berhasil diperbarui';
            if ($totalBobotNonNarasi == 100) {
                $message .= ' (Total bobot sekarang 100%)';
            } elseif ($totalBobotNonNarasi < 100) {
                $message .= " (Total bobot: {$totalBobotNonNarasi}%, masih kurang " . (100 - $totalBobotNonNarasi) . '%)';
            }

            return redirect()->route('admin.kriteria.template', $templateItem->kriteria_id)
                ->with('success', $message);
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

            // Check total
            $totalBobotNonNarasi = TemplateItem::where('kriteria_id', $kriteriaId)
                ->where('tipe', '!=', 'narasi')
                ->sum('bobot');

            $message = 'Template item berhasil dihapus';
            if ($totalBobotNonNarasi < 100) {
                $message .= " (Total bobot sekarang {$totalBobotNonNarasi}%, kurang " . (100 - $totalBobotNonNarasi) . '%)';
            }

            return redirect()->route('admin.kriteria.template', $kriteriaId)
                ->with('success', $message);
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menghapus template item: ' . $e->getMessage());
        }
    }
}
