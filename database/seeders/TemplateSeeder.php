<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\TemplateItem;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Membuat template items untuk setiap sub-kriteria (Level 1)
     */
    public function run(): void
    {
        // K001.01 - Kejelasan Visi Program Studi
        $k00101 = Kriteria::where('kode', 'K001.01')->first();
        if ($k00101) {
            TemplateItem::create([
                'kriteria_id' => $k00101->kriteria_id,
                'tipe' => 'narasi',
                'label' => 'Rumusan Visi Program Studi',
                'hint' => 'Jelaskan visi program studi secara singkat dan jelas',
                'bobot' => 3,
                'urutan' => 1,
                'wajib' => 1,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00101->kriteria_id,
                'tipe' => 'upload',
                'label' => 'Dokumen Visi Program Studi (PDF/DOCX)',
                'hint' => 'Upload dokumen resmi visi program studi dari universitas',
                'bobot' => 2,
                'urutan' => 2,
                'wajib' => 1,
            ]);
        }

        // K001.02 - Relevansi Misi Program Studi
        $k00102 = Kriteria::where('kode', 'K001.02')->first();
        if ($k00102) {
            TemplateItem::create([
                'kriteria_id' => $k00102->kriteria_id,
                'tipe' => 'narasi',
                'label' => 'Rumusan Misi Program Studi',
                'hint' => 'Jelaskan misi program studi dan relevansinya dengan bidang studi',
                'bobot' => 3,
                'urutan' => 1,
                'wajib' => 1,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00102->kriteria_id,
                'tipe' => 'upload',
                'label' => 'Dokumen Misi Program Studi',
                'hint' => 'Upload dokumen resmi misi program studi',
                'bobot' => 2,
                'urutan' => 2,
                'wajib' => 1,
            ]);
        }

        // K001.03 - Kesesuaian Tujuan Program Studi
        $k00103 = Kriteria::where('kode', 'K001.03')->first();
        if ($k00103) {
            TemplateItem::create([
                'kriteria_id' => $k00103->kriteria_id,
                'tipe' => 'narasi',
                'label' => 'Rumusan Tujuan Program Studi (SMART)',
                'hint' => 'Jelaskan tujuan program studi yang terukur (Specific, Measurable, Achievable, Relevant, Time-bound)',
                'bobot' => 3,
                'urutan' => 1,
                'wajib' => 1,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00103->kriteria_id,
                'tipe' => 'checklist',
                'label' => 'Tujuan sesuai dengan Misi Program Studi',
                'hint' => 'Centang jika tujuan program studi sesuai dengan misi yang telah ditetapkan',
                'bobot' => 2,
                'urutan' => 2,
                'wajib' => 1,
            ]);
        }

        // K002.01 - Capaian Pembelajaran Lulusan (CPL)
        $k00201 = Kriteria::where('kode', 'K002.01')->first();
        if ($k00201) {
            TemplateItem::create([
                'kriteria_id' => $k00201->kriteria_id,
                'tipe' => 'narasi',
                'label' => 'Rumusan Capaian Pembelajaran Lulusan (CPL)',
                'hint' => 'Jelaskan CPL yang mencakup knowledge, skills, dan attitudes',
                'bobot' => 4,
                'urutan' => 1,
                'wajib' => 1,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00201->kriteria_id,
                'tipe' => 'upload',
                'label' => 'Dokumen CPL dan Profil Lulusan',
                'hint' => 'Upload dokumen resmi CPL dan profil lulusan yang disetujui',
                'bobot' => 2,
                'urutan' => 2,
                'wajib' => 1,
            ]);
        }

        // K002.02 - Struktur dan Isi Kurikulum
        $k00202 = Kriteria::where('kode', 'K002.02')->first();
        if ($k00202) {
            TemplateItem::create([
                'kriteria_id' => $k00202->kriteria_id,
                'tipe' => 'upload',
                'label' => 'Struktur Kurikulum (PDF/XLSX)',
                'hint' => 'Upload struktur kurikulum dengan mata kuliah, SKS, dan distribusi per semester',
                'bobot' => 3,
                'urutan' => 1,
                'wajib' => 1,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00202->kriteria_id,
                'tipe' => 'narasi',
                'label' => 'Deskripsi Kesesuaian Kurikulum dengan CPL',
                'hint' => 'Jelaskan bagaimana struktur kurikulum mendukung pencapaian CPL',
                'bobot' => 3,
                'urutan' => 2,
                'wajib' => 1,
            ]);
        }

        // K002.03 - Metode dan Proses Pembelajaran
        $k00203 = Kriteria::where('kode', 'K002.03')->first();
        if ($k00203) {
            TemplateItem::create([
                'kriteria_id' => $k00203->kriteria_id,
                'tipe' => 'narasi',
                'label' => 'Deskripsi Metode Pembelajaran',
                'hint' => 'Jelaskan metode pembelajaran yang digunakan (e.g., case study, project-based learning)',
                'bobot' => 2,
                'urutan' => 1,
                'wajib' => 1,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00203->kriteria_id,
                'tipe' => 'checklist',
                'label' => 'Metode pembelajaran mendukung pencapaian CPL',
                'hint' => 'Centang jika metode pembelajaran yang digunakan mendukung pencapaian CPL',
                'bobot' => 2,
                'urutan' => 2,
                'wajib' => 1,
            ]);
        }

        // K002.04 - Evaluasi Pembelajaran
        $k00204 = Kriteria::where('kode', 'K002.04')->first();
        if ($k00204) {
            TemplateItem::create([
                'kriteria_id' => $k00204->kriteria_id,
                'tipe' => 'narasi',
                'label' => 'Sistem Evaluasi Pembelajaran',
                'hint' => 'Jelaskan sistem evaluasi pembelajaran dan frekuensi pelaksanaannya',
                'bobot' => 2,
                'urutan' => 1,
                'wajib' => 1,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00204->kriteria_id,
                'tipe' => 'upload',
                'label' => 'Laporan Hasil Evaluasi Pembelajaran',
                'hint' => 'Upload laporan hasil evaluasi pembelajaran dari tahun sebelumnya',
                'bobot' => 2,
                'urutan' => 2,
                'wajib' => 0,
            ]);
        }

        // K003.01 - Kualifikasi Dosen
        $k00301 = Kriteria::where('kode', 'K003.01')->first();
        if ($k00301) {
            TemplateItem::create([
                'kriteria_id' => $k00301->kriteria_id,
                'tipe' => 'numerik',
                'label' => 'Jumlah Dosen S3 (%)',
                'hint' => 'Masukkan persentase dosen dengan kualifikasi S3',
                'bobot' => 2.5,
                'urutan' => 1,
                'wajib' => 1,
                'nilai_min_numerik' => 50,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00301->kriteria_id,
                'tipe' => 'upload',
                'label' => 'Data Dosen dan Kualifikasi Akademik',
                'hint' => 'Upload file berisi data dosen dengan riwayat pendidikan',
                'bobot' => 2.5,
                'urutan' => 2,
                'wajib' => 1,
            ]);
        }

        // K003.02 - Kompetensi dan Keahlian Dosen
        $k00302 = Kriteria::where('kode', 'K003.02')->first();
        if ($k00302) {
            TemplateItem::create([
                'kriteria_id' => $k00302->kriteria_id,
                'tipe' => 'narasi',
                'label' => 'Deskripsi Kompetensi Dosen per Mata Kuliah',
                'hint' => 'Jelaskan keselarasan kompetensi dosen dengan mata kuliah yang diampu',
                'bobot' => 3,
                'urutan' => 1,
                'wajib' => 1,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00302->kriteria_id,
                'tipe' => 'checklist',
                'label' => 'Ada bukti sertifikasi keahlian dosen',
                'hint' => 'Centang jika ada sertifikasi atau pengakuan keahlian untuk dosen',
                'bobot' => 2,
                'urutan' => 2,
                'wajib' => 0,
            ]);
        }

        // K003.03 - Pengembangan Profesional Dosen
        $k00303 = Kriteria::where('kode', 'K003.03')->first();
        if ($k00303) {
            TemplateItem::create([
                'kriteria_id' => $k00303->kriteria_id,
                'tipe' => 'numerik',
                'label' => 'Rata-rata jam pelatihan dosen per tahun',
                'hint' => 'Masukkan rata-rata jam pelatihan/pengembangan yang diikuti dosen per tahun',
                'bobot' => 2.5,
                'urutan' => 1,
                'wajib' => 1,
                'nilai_min_numerik' => 40,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00303->kriteria_id,
                'tipe' => 'upload',
                'label' => 'Laporan Pengembangan Profesional Dosen',
                'hint' => 'Upload laporan aktivitas pengembangan profesional dosen (pelatihan, workshop, dll)',
                'bobot' => 2.5,
                'urutan' => 2,
                'wajib' => 1,
            ]);
        }

        // K004.01 - Ruang Kuliah dan Laboratorium
        $k00401 = Kriteria::where('kode', 'K004.01')->first();
        if ($k00401) {
            TemplateItem::create([
                'kriteria_id' => $k00401->kriteria_id,
                'tipe' => 'upload',
                'label' => 'Inventaris Ruang Kuliah dan Laboratorium',
                'hint' => 'Upload daftar lengkap ruang kuliah dan laboratorium dengan kapasitas',
                'bobot' => 3,
                'urutan' => 1,
                'wajib' => 1,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00401->kriteria_id,
                'tipe' => 'narasi',
                'label' => 'Deskripsi Peralatan Laboratorium',
                'hint' => 'Jelaskan peralatan laboratorium utama dan kondisinya',
                'bobot' => 3,
                'urutan' => 2,
                'wajib' => 0,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00401->kriteria_id,
                'tipe' => 'checklist',
                'label' => 'Ruang kuliah dan lab memenuhi standar kebersihan dan keselamatan',
                'hint' => 'Centang jika semua ruang memenuhi standar',
                'bobot' => 2,
                'urutan' => 3,
                'wajib' => 1,
            ]);
        }

        // K004.02 - Perpustakaan dan Sumber Belajar
        $k00402 = Kriteria::where('kode', 'K004.02')->first();
        if ($k00402) {
            TemplateItem::create([
                'kriteria_id' => $k00402->kriteria_id,
                'tipe' => 'numerik',
                'label' => 'Jumlah Judul Buku Referensi',
                'hint' => 'Masukkan jumlah judul buku referensi dalam perpustakaan',
                'bobot' => 2,
                'urutan' => 1,
                'wajib' => 1,
                'nilai_min_numerik' => 100,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00402->kriteria_id,
                'tipe' => 'numerik',
                'label' => 'Jumlah Langganan Jurnal Elektronik',
                'hint' => 'Masukkan jumlah jurnal elektronik yang dilanggani',
                'bobot' => 2,
                'urutan' => 2,
                'wajib' => 1,
                'nilai_min_numerik' => 5,
            ]);
        }

        // K004.03 - Teknologi Informasi dan Sarana Penunjang
        $k00403 = Kriteria::where('kode', 'K004.03')->first();
        if ($k00403) {
            TemplateItem::create([
                'kriteria_id' => $k00403->kriteria_id,
                'tipe' => 'upload',
                'label' => 'Inventaris Komputer dan Server',
                'hint' => 'Upload daftar komputer, server, dan infrastruktur TI',
                'bobot' => 1.5,
                'urutan' => 1,
                'wajib' => 1,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00403->kriteria_id,
                'tipe' => 'checklist',
                'label' => 'Tersedia bandwidth internet yang memadai',
                'hint' => 'Centang jika bandwidth internet mencukupi untuk pembelajaran online',
                'bobot' => 1.5,
                'urutan' => 2,
                'wajib' => 1,
            ]);
        }

        // K005.01 - Tata Pamong dan Organisasi
        $k00501 = Kriteria::where('kode', 'K005.01')->first();
        if ($k00501) {
            TemplateItem::create([
                'kriteria_id' => $k00501->kriteria_id,
                'tipe' => 'upload',
                'label' => 'Struktur Organisasi Program Studi',
                'hint' => 'Upload struktur organisasi program studi dengan penjelasan tugas',
                'bobot' => 3,
                'urutan' => 1,
                'wajib' => 1,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00501->kriteria_id,
                'tipe' => 'upload',
                'label' => 'Standar Operasional Prosedur (SOP)',
                'hint' => 'Upload SOP untuk proses akademik dan administratif',
                'bobot' => 2,
                'urutan' => 2,
                'wajib' => 1,
            ]);
        }

        // K005.02 - Sistem Manajemen Mutu
        $k00502 = Kriteria::where('kode', 'K005.02')->first();
        if ($k00502) {
            TemplateItem::create([
                'kriteria_id' => $k00502->kriteria_id,
                'tipe' => 'narasi',
                'label' => 'Deskripsi Sistem Manajemen Mutu Program Studi',
                'hint' => 'Jelaskan sistem manajemen mutu yang diterapkan',
                'bobot' => 2.5,
                'urutan' => 1,
                'wajib' => 1,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00502->kriteria_id,
                'tipe' => 'upload',
                'label' => 'Dokumen Audit Internal dan Hasil Perbaikan',
                'hint' => 'Upload laporan audit internal dan tindakan perbaikan yang dilakukan',
                'bobot' => 2.5,
                'urutan' => 2,
                'wajib' => 1,
            ]);
        }

        // K005.03 - Kerjasama dan Kemitraan
        $k00503 = Kriteria::where('kode', 'K005.03')->first();
        if ($k00503) {
            TemplateItem::create([
                'kriteria_id' => $k00503->kriteria_id,
                'tipe' => 'upload',
                'label' => 'Daftar Kemitraan Strategis dengan Industri/Institusi',
                'hint' => 'Upload daftar perjanjian kerjasama dan MoU dengan mitra',
                'bobot' => 3,
                'urutan' => 1,
                'wajib' => 1,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00503->kriteria_id,
                'tipe' => 'narasi',
                'label' => 'Deskripsi Manfaat Kerjasama untuk Program Studi',
                'hint' => 'Jelaskan bagaimana kerjasama memberikan dampak positif',
                'bobot' => 2,
                'urutan' => 2,
                'wajib' => 0,
            ]);
        }

        // K006.01 - Produktivitas Penelitian
        $k00601 = Kriteria::where('kode', 'K006.01')->first();
        if ($k00601) {
            TemplateItem::create([
                'kriteria_id' => $k00601->kriteria_id,
                'tipe' => 'numerik',
                'label' => 'Jumlah Publikasi Ilmiah (3 tahun terakhir)',
                'hint' => 'Masukkan jumlah publikasi di jurnal nasional dan internasional',
                'bobot' => 3,
                'urutan' => 1,
                'wajib' => 1,
                'nilai_min_numerik' => 10,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00601->kriteria_id,
                'tipe' => 'upload',
                'label' => 'Bukti Publikasi dan Sitasi',
                'hint' => 'Upload daftar publikasi dengan bukti link/DOI',
                'bobot' => 2,
                'urutan' => 2,
                'wajib' => 1,
            ]);
        }

        // K006.02 - Pengabdian Masyarakat
        $k00602 = Kriteria::where('kode', 'K006.02')->first();
        if ($k00602) {
            TemplateItem::create([
                'kriteria_id' => $k00602->kriteria_id,
                'tipe' => 'numerik',
                'label' => 'Jumlah Program Pengabdian Masyarakat (3 tahun terakhir)',
                'hint' => 'Masukkan jumlah program pengabdian yang dilaksanakan',
                'bobot' => 2.5,
                'urutan' => 1,
                'wajib' => 1,
                'nilai_min_numerik' => 5,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00602->kriteria_id,
                'tipe' => 'upload',
                'label' => 'Laporan Program Pengabdian Masyarakat',
                'hint' => 'Upload laporan pelaksanaan pengabdian masyarakat',
                'bobot' => 2.5,
                'urutan' => 2,
                'wajib' => 1,
            ]);
        }

        // K007.01 - Kompetensi Lulusan
        $k00701 = Kriteria::where('kode', 'K007.01')->first();
        if ($k00701) {
            TemplateItem::create([
                'kriteria_id' => $k00701->kriteria_id,
                'tipe' => 'upload',
                'label' => 'Hasil Tracer Study Lulusan',
                'hint' => 'Upload hasil tracer study tentang kompetensi lulusan menurut pengguna',
                'bobot' => 3,
                'urutan' => 1,
                'wajib' => 1,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00701->kriteria_id,
                'tipe' => 'numerik',
                'label' => 'Tingkat Kepuasan Pengguna Lulusan (%)',
                'hint' => 'Masukkan persentase kepuasan pengguna terhadap kompetensi lulusan',
                'bobot' => 2,
                'urutan' => 2,
                'wajib' => 1,
                'nilai_min_numerik' => 75,
            ]);
        }

        // K007.02 - Penempatan dan Relevansi Kerja
        $k00702 = Kriteria::where('kode', 'K007.02')->first();
        if ($k00702) {
            TemplateItem::create([
                'kriteria_id' => $k00702->kriteria_id,
                'tipe' => 'numerik',
                'label' => 'Tingkat Penempatan Lulusan (%)',
                'hint' => 'Masukkan persentase lulusan yang terserap di dunia kerja',
                'bobot' => 2.5,
                'urutan' => 1,
                'wajib' => 1,
                'nilai_min_numerik' => 85,
            ]);

            TemplateItem::create([
                'kriteria_id' => $k00702->kriteria_id,
                'tipe' => 'upload',
                'label' => 'Data Perusahaan Rekrutan Lulusan',
                'hint' => 'Upload daftar perusahaan/institusi yang merekrut lulusan program studi',
                'bobot' => 2.5,
                'urutan' => 2,
                'wajib' => 0,
            ]);
        }
    }
}
