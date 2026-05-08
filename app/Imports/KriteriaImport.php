<?php

namespace App\Imports;

use App\Models\Kriteria;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class KriteriaImport implements ToCollection, WithHeadingRow
{
    /**
     * Import kriteria from Excel file
     * Processes hierarchical structure: Level 0 -> Level 1 -> Level 2
     * 
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            // Map to store kode => kriteria_id for parent lookup
            $parentMap = [];

            // Sort by level to ensure parents are created before children
            $sortedRows = $rows->sortBy('level_012');

            foreach ($sortedRows as $row) {
                // Skip empty rows
                if (empty($row['kode']) || empty($row['nama'])) {
                    continue;
                }

                // Determine parent_id from parent_kode
                $parentId = null;
                $parentKode = $row['parent_kode_kosongkan_jika_level_0'] ?? '';
                
                if (!empty($parentKode) && isset($parentMap[$parentKode])) {
                    $parentId = $parentMap[$parentKode];
                }

                // Create or update kriteria
                $kriteria = Kriteria::updateOrCreate(
                    ['kode' => $row['kode']],
                    [
                        'nama' => $row['nama'],
                        'deskripsi' => $row['deskripsi'] ?? '',
                        'level' => $row['level_012'] ?? 0,
                        'bobot' => $row['bobot'] ?? 0,
                        'urutan' => $row['urutan'] ?? 1,
                        'parent_id' => $parentId,
                    ]
                );

                // Store in map for child references
                $parentMap[$row['kode']] = $kriteria->kriteria_id;
            }
        });
    }
}
