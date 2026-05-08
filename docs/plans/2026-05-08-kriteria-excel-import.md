# Kriteria Excel Import Implementation Plan

> **REQUIRED SUB-SKILL:** Use the executing-plans skill to implement this plan task-by-task.

**Goal:** Provide an Excel import and template download feature for `Kriteria` hierarchy based on the existing structure in `KriteriaSeeder`.

**Architecture:** Use `maatwebsite/excel` to generate an Excel template matching the required seeder structure. Provide an import mechanism that reads the template, drops existing data, and recreates the hierarchy using chunk reading and model importing.

**Tech Stack:** Laravel, Maatwebsite Excel 3.1, Eloquent

---

### Task 1: Create KriteriaExport class for Template

**TDD scenario:** Modifying tested code — run existing tests first or create basic structure if no tests exist.

**Files:**
- Create: `app/Exports/KriteriaTemplateExport.php`

**Step 1: Write export class implementation**

```php
<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KriteriaTemplateExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize
{
    public function array(): array
    {
        return [
            ['K1', 'Orientasi Kompetensi Lulusan', 'Kriteria 1: Penetapan, penyebaran, dan kaji ulang PPM dan CPL.', 0, 28, 1, ''],
            ['K1.1', 'Penetapan Profil Profesional Mandiri (PPM)', 'Prodi menetapkan profil lulusan...', 1, 2, 1, 'K1'],
            ['K1.1.1', 'Profil Profesional Mandiri (PPM) Prodi', 'Paparkan Profil Profesional...', 2, 1, 1, 'K1.1'],
        ];
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Nama',
            'Deskripsi',
            'Level (0/1/2)',
            'Bobot',
            'Urutan',
            'Parent Kode (Kosongkan jika Level 0)'
        ];
    }

    public function title(): string
    {
        return 'Template Kriteria';
    }
}
```

**Step 2: Commit**

```bash
git add app/Exports/KriteriaTemplateExport.php
git commit -m "feat: add KriteriaTemplateExport class"
```

### Task 2: Create KriteriaImport class

**TDD scenario:** Trivial change — use judgment

**Files:**
- Create: `app/Imports/KriteriaImport.php`

**Step 1: Write import class implementation**

```php
<?php

namespace App\Imports;

use App\Models\Kriteria;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class KriteriaImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            // Drop existing sub-kriteria submission template if necessary based on rules, or just wipe for fresh import
            // Note: If foreign keys constraint block, handle appropriately. For safety, typically check empty db or manual deletion first.
            // For now, mapping logic:
            
            $parentMap = []; // code => id

            foreach ($rows as $row) {
                // Ensure required fields
                if (empty($row['kode']) || empty($row['nama'])) continue;

                $parentId = null;
                if (!empty($row['parent_kode'])) {
                    $parentId = $parentMap[$row['parent_kode']] ?? null;
                }

                $kriteria = Kriteria::updateOrCreate(
                    ['kode' => $row['kode']],
                    [
                        'nama' => $row['nama'],
                        'deskripsi' => $row['deskripsi'] ?? '',
                        'level' => $row['level_012'] ?? $row['level'], // Based on slugified heading
                        'bobot' => $row['bobot'] ?? 0,
                        'urutan' => $row['urutan'] ?? 1,
                        'parent_id' => $parentId,
                    ]
                );

                $parentMap[$row['kode']] = $kriteria->kriteria_id;
            }
        });
    }
}
```

**Step 2: Commit**

```bash
git add app/Imports/KriteriaImport.php
git commit -m "feat: add KriteriaImport class"
```

### Task 3: Add Routes for Import and Export

**TDD scenario:** Trivial change — use judgment

**Files:**
- Modify: `routes/web.php` (or wherever admin kriteria routes are defined)

**Step 1: Write route modifications**
Identify the route group for `admin.kriteria`.

```php
// Add before the resource or inside the group
Route::get('admin/kriteria/download-template', [App\Http\Controllers\Admin\KriteriaController::class, 'downloadTemplate'])->name('admin.kriteria.download-template');
Route::post('admin/kriteria/import', [App\Http\Controllers\Admin\KriteriaController::class, 'import'])->name('admin.kriteria.import');
```

**Step 2: Commit**

```bash
git commit -am "feat: add import and export routes for kriteria"
```

### Task 4: Update KriteriaController

**TDD scenario:** Trivial change — use judgment

**Files:**
- Modify: `app/Http/Controllers/Admin/KriteriaController.php`

**Step 1: Write controller methods**

```php
use App\Exports\KriteriaTemplateExport;
use App\Imports\KriteriaImport;
use Maatwebsite\Excel\Facades\Excel;

// Add these methods inside KriteriaController:

public function downloadTemplate()
{
    return Excel::download(new KriteriaTemplateExport, 'template_kriteria.xlsx');
}

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv|max:2048'
    ]);

    try {
        Excel::import(new KriteriaImport, $request->file('file'));
        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Data kriteria berhasil diimport');
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
    }
}
```

**Step 2: Commit**

```bash
git commit -am "feat: add downloadTemplate and import methods to KriteriaController"
```

### Task 5: Update View with Import Modal and Buttons

**TDD scenario:** Trivial change — use judgment

**Files:**
- Modify: `resources/views/admin/kriteria/index.blade.php`

**Step 1: Add buttons and modal in the view**
In the header actions, add Download Template and Import buttons:

```html
<a href="{{ route('admin.kriteria.download-template') }}" class="...">Download Template</a>
<button onclick="openImportModal()" class="...">Import Excel</button>
```

Add the Import Modal at the bottom:

```html
<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-500 bg-opacity-75" aria-hidden="true" onclick="closeImportModal()"></div>
        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <form action="{{ route('admin.kriteria.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div>
                    <h3 class="text-lg font-medium leading-6 text-slate-900" id="modal-title">Import Data Kriteria</h3>
                    <div class="mt-2">
                        <p class="text-sm text-slate-500">Pilih file Excel sesuai template yang telah disediakan.</p>
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="mt-4 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none sm:col-start-2 sm:text-sm">Import</button>
                    <button type="button" onclick="closeImportModal()" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-slate-700 bg-white border border-slate-300 rounded-md shadow-sm hover:bg-slate-50 focus:outline-none sm:mt-0 sm:col-start-1 sm:text-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openImportModal() {
        document.getElementById('importModal').classList.remove('hidden');
    }
    function closeImportModal() {
        document.getElementById('importModal').classList.add('hidden');
    }
</script>
```

**Step 2: Commit**

```bash
git commit -am "feat: add import modal and download template button to view"
```