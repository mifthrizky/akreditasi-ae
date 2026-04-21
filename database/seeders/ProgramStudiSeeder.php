<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // TRO
        ProgramStudi::create([
            'kode' => 'TRO',
            'nama' => 'Teknik Rekayasa Otomasi',
            'jurusan' => 'AE',
        ]);

        // TRMO
        ProgramStudi::create([
            'kode' => 'TRMO',
            'nama' => 'Teknik Rekayasa Mekatronika',
            'jurusan' => 'AE',
        ]);

        // TRIN
        ProgramStudi::create([
            'kode' => 'TRIN',
            'nama' => 'Teknologi Rekayasa Informatika Industri',
            'jurusan' => 'AE',
        ]);

         // TRSA
        ProgramStudi::create([
            'kode' => 'TRSA',
            'nama' => 'Teknologi Rekayasa Sistem Aerial Nirawak',
            'jurusan' => 'AE',
        ]);
    }
}
