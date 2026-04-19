<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use Illuminate\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Level 0 Kriteria (Utama/Main Criteria)
        $kriteria0_1 = Kriteria::create([
            'kode' => 'K001',
            'nama' => 'Visi, Misi, dan Tujuan Program Studi',
            'deskripsi' => 'Evaluasi kesesuaian visi, misi, dan tujuan program studi dengan tujuan pendidikan tinggi',
            'level' => 0,
            'bobot' => 15,
            'urutan' => 1,
        ]);

        $kriteria0_2 = Kriteria::create([
            'kode' => 'K002',
            'nama' => 'Kurikulum dan Pembelajaran',
            'deskripsi' => 'Evaluasi kualitas kurikulum, pembelajaran, dan pencapaian kompetensi lulusan',
            'level' => 0,
            'bobot' => 20,
            'urutan' => 2,
        ]);

        $kriteria0_3 = Kriteria::create([
            'kode' => 'K003',
            'nama' => 'Dosen dan Tenaga Kependidikan',
            'deskripsi' => 'Evaluasi kualifikasi, kompetensi, dan pengembangan dosen dan tenaga kependidikan',
            'level' => 0,
            'bobot' => 15,
            'urutan' => 3,
        ]);

        $kriteria0_4 = Kriteria::create([
            'kode' => 'K004',
            'nama' => 'Sarana dan Prasarana',
            'deskripsi' => 'Evaluasi kecukupan dan kualitas sarana dan prasarana pembelajaran',
            'level' => 0,
            'bobot' => 15,
            'urutan' => 4,
        ]);

        $kriteria0_5 = Kriteria::create([
            'kode' => 'K005',
            'nama' => 'Manajemen Program Studi',
            'deskripsi' => 'Evaluasi efektivitas manajemen dan tata pamong program studi',
            'level' => 0,
            'bobot' => 15,
            'urutan' => 5,
        ]);

        $kriteria0_6 = Kriteria::create([
            'kode' => 'K006',
            'nama' => 'Penelitian dan Pengabdian Masyarakat',
            'deskripsi' => 'Evaluasi kualitas penelitian dan pengabdian masyarakat',
            'level' => 0,
            'bobot' => 10,
            'urutan' => 6,
        ]);

        $kriteria0_7 = Kriteria::create([
            'kode' => 'K007',
            'nama' => 'Lulusan dan Relevansi Terhadap Kebutuhan Masyarakat',
            'deskripsi' => 'Evaluasi relevansi lulusan dengan kebutuhan pasar kerja',
            'level' => 0,
            'bobot' => 10,
            'urutan' => 7,
        ]);

        // Level 1 Kriteria - Visi, Misi, dan Tujuan
        Kriteria::create([
            'parent_id' => $kriteria0_1->kriteria_id,
            'kode' => 'K001.01',
            'nama' => 'Kejelasan Visi Program Studi',
            'deskripsi' => 'Visi program studi harus jelas, terukur, dan sesuai dengan visi universitas',
            'level' => 1,
            'bobot' => 5,
            'urutan' => 1,
        ]);

        Kriteria::create([
            'parent_id' => $kriteria0_1->kriteria_id,
            'kode' => 'K001.02',
            'nama' => 'Relevansi Misi Program Studi',
            'deskripsi' => 'Misi program studi harus relevan dengan bidang studi dan perkembangan ilmu',
            'level' => 1,
            'bobot' => 5,
            'urutan' => 2,
        ]);

        Kriteria::create([
            'parent_id' => $kriteria0_1->kriteria_id,
            'kode' => 'K001.03',
            'nama' => 'Kesesuaian Tujuan Program Studi',
            'deskripsi' => 'Tujuan program studi harus terukur dan sesuai dengan misi',
            'level' => 1,
            'bobot' => 5,
            'urutan' => 3,
        ]);

        // Level 1 Kriteria - Kurikulum dan Pembelajaran
        Kriteria::create([
            'parent_id' => $kriteria0_2->kriteria_id,
            'kode' => 'K002.01',
            'nama' => 'Capaian Pembelajaran Lulusan (CPL)',
            'deskripsi' => 'CPL harus dirumuskan berdasarkan profil lulusan dan kebutuhan stakeholder',
            'level' => 1,
            'bobot' => 6,
            'urutan' => 1,
        ]);

        Kriteria::create([
            'parent_id' => $kriteria0_2->kriteria_id,
            'kode' => 'K002.02',
            'nama' => 'Struktur dan Isi Kurikulum',
            'deskripsi' => 'Kurikulum harus terstruktur dan mencakup kompetensi yang sesuai',
            'level' => 1,
            'bobot' => 6,
            'urutan' => 2,
        ]);

        Kriteria::create([
            'parent_id' => $kriteria0_2->kriteria_id,
            'kode' => 'K002.03',
            'nama' => 'Metode dan Proses Pembelajaran',
            'deskripsi' => 'Metode pembelajaran harus efektif dan mendukung pencapaian CPL',
            'level' => 1,
            'bobot' => 4,
            'urutan' => 3,
        ]);

        Kriteria::create([
            'parent_id' => $kriteria0_2->kriteria_id,
            'kode' => 'K002.04',
            'nama' => 'Evaluasi Pembelajaran',
            'deskripsi' => 'Evaluasi pembelajaran harus dilakukan secara berkala dan terukur',
            'level' => 1,
            'bobot' => 4,
            'urutan' => 4,
        ]);

        // Level 1 Kriteria - Dosen dan Tenaga Kependidikan
        Kriteria::create([
            'parent_id' => $kriteria0_3->kriteria_id,
            'kode' => 'K003.01',
            'nama' => 'Kualifikasi Dosen',
            'deskripsi' => 'Dosen harus memiliki kualifikasi akademik minimal S2 di bidang relevan',
            'level' => 1,
            'bobot' => 5,
            'urutan' => 1,
        ]);

        Kriteria::create([
            'parent_id' => $kriteria0_3->kriteria_id,
            'kode' => 'K003.02',
            'nama' => 'Kompetensi dan Keahlian Dosen',
            'deskripsi' => 'Dosen harus memiliki kompetensi sesuai dengan bidang ajar',
            'level' => 1,
            'bobot' => 5,
            'urutan' => 2,
        ]);

        Kriteria::create([
            'parent_id' => $kriteria0_3->kriteria_id,
            'kode' => 'K003.03',
            'nama' => 'Pengembangan Profesional Dosen',
            'deskripsi' => 'Dosen harus mengikuti pengembangan profesional berkelanjutan',
            'level' => 1,
            'bobot' => 5,
            'urutan' => 3,
        ]);

        // Level 1 Kriteria - Sarana dan Prasarana
        Kriteria::create([
            'parent_id' => $kriteria0_4->kriteria_id,
            'kode' => 'K004.01',
            'nama' => 'Ruang Kuliah dan Laboratorium',
            'deskripsi' => 'Ruang kuliah dan laboratorium harus memadai dan dilengkapi peralatan',
            'level' => 1,
            'bobot' => 8,
            'urutan' => 1,
        ]);

        Kriteria::create([
            'parent_id' => $kriteria0_4->kriteria_id,
            'kode' => 'K004.02',
            'nama' => 'Perpustakaan dan Sumber Belajar',
            'deskripsi' => 'Perpustakaan harus menyediakan koleksi buku dan jurnal yang cukup',
            'level' => 1,
            'bobot' => 4,
            'urutan' => 2,
        ]);

        Kriteria::create([
            'parent_id' => $kriteria0_4->kriteria_id,
            'kode' => 'K004.03',
            'nama' => 'Teknologi Informasi dan Sarana Penunjang',
            'deskripsi' => 'Sarana TI harus memadai untuk mendukung proses belajar mengajar',
            'level' => 1,
            'bobot' => 3,
            'urutan' => 3,
        ]);

        // Level 1 Kriteria - Manajemen Program Studi
        Kriteria::create([
            'parent_id' => $kriteria0_5->kriteria_id,
            'kode' => 'K005.01',
            'nama' => 'Tata Pamong dan Organisasi',
            'deskripsi' => 'Tata pamong harus jelas dengan struktur organisasi yang efektif',
            'level' => 1,
            'bobot' => 5,
            'urutan' => 1,
        ]);

        Kriteria::create([
            'parent_id' => $kriteria0_5->kriteria_id,
            'kode' => 'K005.02',
            'nama' => 'Sistem Manajemen Mutu',
            'deskripsi' => 'Sistem manajemen mutu harus terstruktur dan terimplementasi',
            'level' => 1,
            'bobot' => 5,
            'urutan' => 2,
        ]);

        Kriteria::create([
            'parent_id' => $kriteria0_5->kriteria_id,
            'kode' => 'K005.03',
            'nama' => 'Kerjasama dan Kemitraan',
            'deskripsi' => 'Program studi harus membangun kerjasama dengan industri dan institusi lain',
            'level' => 1,
            'bobot' => 5,
            'urutan' => 3,
        ]);

        // Level 1 Kriteria - Penelitian dan Pengabdian Masyarakat
        Kriteria::create([
            'parent_id' => $kriteria0_6->kriteria_id,
            'kode' => 'K006.01',
            'nama' => 'Produktivitas Penelitian',
            'deskripsi' => 'Dosen harus aktif melakukan penelitian dengan publikasi yang terukur',
            'level' => 1,
            'bobot' => 5,
            'urutan' => 1,
        ]);

        Kriteria::create([
            'parent_id' => $kriteria0_6->kriteria_id,
            'kode' => 'K006.02',
            'nama' => 'Pengabdian Masyarakat',
            'deskripsi' => 'Program pengabdian masyarakat harus terstruktur dan relevan',
            'level' => 1,
            'bobot' => 5,
            'urutan' => 2,
        ]);

        // Level 1 Kriteria - Lulusan
        Kriteria::create([
            'parent_id' => $kriteria0_7->kriteria_id,
            'kode' => 'K007.01',
            'nama' => 'Kompetensi Lulusan',
            'deskripsi' => 'Lulusan harus menguasai kompetensi sesuai dengan CPL',
            'level' => 1,
            'bobot' => 5,
            'urutan' => 1,
        ]);

        Kriteria::create([
            'parent_id' => $kriteria0_7->kriteria_id,
            'kode' => 'K007.02',
            'nama' => 'Penempatan dan Relevansi Kerja',
            'deskripsi' => 'Lulusan harus dapat diterima di dunia kerja dan relevan dengan kebutuhan',
            'level' => 1,
            'bobot' => 5,
            'urutan' => 2,
        ]);
    }
}
