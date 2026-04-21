<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\TemplateItem;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Membuat template items untuk seluruh sub-kriteria Level 2 IABEE 2026 (29 item).
     *
     * Aturan bobot per PRD:
     *   - Total bobot item wajib (wajib=true) per sub-kriteria = 100
     *   - Item tipe 'narasi' WAJIB berbobot = 0
     *   - Item tipe 'upload', 'checklist', 'numerik' berkontribusi ke skor
     *
     * Pola template yang digunakan:
     *   [A] Standar   : narasi(0) + upload_utama(70) + checklist(30)          = 100
     *   [B] Dua Dokumen: narasi(0) + upload_utama(60) + upload_pendukung(40)  = 100
     *   [C] Kuantitatif: narasi(0) + numerik(40) + upload(60)                 = 100
     *   [D] Dokumen saja: narasi(0) + upload(100)                             = 100
     */
    public function run(): void
    {
        // =====================================================================
        // KRITERIA 1 — ORIENTASI KOMPETENSI LULUSAN
        // =====================================================================

        // ── K1.1.1: Profil Profesional Mandiri (PPM) Prodi ───────────────────
        // Referensi Borang: Dokumen Kurikulum, Buku Panduan Akademik, Renstra
        $k = Kriteria::where('kode', 'K1.1.1')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Deskripsi Profil Profesional Mandiri (PPM)',
                'hint'        => 'Paparkan Profil Profesional Mandiri Prodi yang ditetapkan sebagai sasaran kependidikan. Sertakan pertimbangan sumberdaya lokal/nasional dan misi POI.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Dokumen Kurikulum Program Studi / Buku Panduan Akademik',
                'hint'        => 'Upload dokumen kurikulum resmi atau buku panduan akademik yang memuat profil lulusan.',
                'bobot'       => 70,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Dokumen Rencana Strategis (Renstra) Prodi',
                'hint'        => 'Upload dokumen Renstra yang relevan dengan penetapan PPM.',
                'bobot'       => 30,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K1.1.2: Proses Penyusunan dan Kaji Ulang PPM ─────────────────────
        // Referensi Borang: SK Satgas, Notulensi Rapat Kurikulum, Dokumen Kaji Ulang
        $k = Kriteria::where('kode', 'K1.1.2')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Penjelasan Proses Penyusunan dan Kaji Ulang PPM',
                'hint'        => 'Jelaskan proses yang diselenggarakan Prodi untuk menyusun dan mengkaji ulang PPM secara berkala beserta mekanisme pelibatan pemangku kepentingan.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'SK Satgas Penyusunan/Kaji Ulang Kurikulum',
                'hint'        => 'Upload SK pembentukan Satgas Penyusunan atau Kaji Ulang Kurikulum.',
                'bobot'       => 50,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Notulensi Rapat dan Dokumen Kaji Ulang Kurikulum',
                'hint'        => 'Upload notulensi rapat kurikulum dan/atau dokumen kaji ulang yang mencantumkan masukan pemangku kepentingan.',
                'bobot'       => 50,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K1.2.1: Penyebarluasan PPM kepada Sivitas Akademika ──────────────
        // Referensi Borang: Buku Panduan, Website, Laporan Sosialisasi, Brosur PMB
        $k = Kriteria::where('kode', 'K1.2.1')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Deskripsi Mekanisme Penyebaran PPM',
                'hint'        => 'Jelaskan bagaimana Prodi menyebarluaskan PPM secara memadai kepada mahasiswa, dosen, dan masyarakat umum (media yang digunakan, frekuensi, dan sasaran).',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Buku Panduan Akademik / Brosur PMB / Laporan Sosialisasi',
                'hint'        => 'Upload salah satu atau lebih dari: Buku Panduan Akademik, Brosur Penerimaan Mahasiswa Baru, atau Laporan Kegiatan Sosialisasi PPM.',
                'bobot'       => 70,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'checklist',
                'label'       => 'PPM tersedia dan dapat diakses di website resmi Prodi',
                'hint'        => 'Centang jika PPM sudah dipublikasikan dan dapat diakses oleh publik melalui website resmi.',
                'bobot'       => 30,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K1.2.2: Capaian Pembelajaran Prodi (CPL) ─────────────────────────
        // Referensi Borang: Dokumen Kurikulum, RPS, Dokumen CPL, Standar Kompetensi Lulusan
        $k = Kriteria::where('kode', 'K1.2.2')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Rumusan CPL Butir (a) hingga (e)',
                'hint'        => 'Paparkan rumusan CPL Prodi yang mencakup: (a) analisis computing kompleks, (b) desain solusi computing, (c) rancangan sistem rekayasa, (d) tanggung jawab profesional dan etika, (e) kemampuan kerja tim/kepemimpinan.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Dokumen Capaian Pembelajaran Lulusan (CPL) / Standar Kompetensi Lulusan',
                'hint'        => 'Upload dokumen resmi CPL atau Standar Kompetensi Lulusan yang telah disahkan.',
                'bobot'       => 60,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Rencana Pembelajaran Semester (RPS) yang Memuat CPL',
                'hint'        => 'Upload contoh RPS yang menunjukkan pemetaan CPL ke mata kuliah.',
                'bobot'       => 40,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K1.2.3: Cakupan CPL Tambahan dari Kriteria Disiplin ──────────────
        // Referensi Borang: Dokumen Kurikulum
        $k = Kriteria::where('kode', 'K1.2.3')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Penjelasan Kesesuaian CPL dengan Kriteria Disiplin',
                'hint'        => 'Jelaskan apakah CPL Prodi telah mencakup capaian pembelajaran tambahan yang diminta oleh Kriteria Disiplin yang relevan. Jika ada, sebutkan dan jelaskan integrasinya.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Dokumen Kurikulum yang Memuat CPL Tambahan',
                'hint'        => 'Upload dokumen kurikulum yang menunjukkan integrasi CPL tambahan sesuai kriteria disiplin.',
                'bobot'       => 70,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'checklist',
                'label'       => 'CPL telah mencakup seluruh persyaratan Kriteria Disiplin yang berlaku',
                'hint'        => 'Centang jika CPL sudah mencakup semua persyaratan tambahan dari Kriteria Disiplin yang relevan.',
                'bobot'       => 30,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K1.3.1: Penyebarluasan PPM dan CPL kepada Publik ─────────────────
        // Referensi Borang: Buku Panduan, Website, Laporan OMB
        $k = Kriteria::where('kode', 'K1.3.1')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Deskripsi Penyebarluasan PPM dan CPL kepada Publik',
                'hint'        => 'Jelaskan bagaimana Program menyebarluaskan PPM dan CPL secara efektif kepada calon mahasiswa, mahasiswa, dosen, dan masyarakat umum, termasuk media dan frekuensinya.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Buku Panduan Akademik / Identitas Program Studi (Website)',
                'hint'        => 'Upload Buku Panduan Akademik atau tautan/screenshot website resmi yang memuat PPM dan CPL.',
                'bobot'       => 60,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Laporan Kegiatan Orientasi Mahasiswa Baru (OMB)',
                'hint'        => 'Upload laporan atau dokumentasi kegiatan orientasi mahasiswa baru yang mencantumkan sosialisasi PPM/CPL.',
                'bobot'       => 40,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K1.3.2: Prosedur Kaji Ulang Berkala CPL ──────────────────────────
        // Referensi Borang: Panduan Teknis Evaluasi, SOP Kaji Ulang CPL, Laporan Evaluasi, SK Tim
        $k = Kriteria::where('kode', 'K1.3.2')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Deskripsi Prosedur Kaji Ulang Berkala CPL',
                'hint'        => 'Paparkan prosedur yang ditetapkan Prodi untuk melaksanakan kaji ulang berkala CPL, termasuk frekuensi, mekanisme, dan pihak yang terlibat.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'SOP Kaji Ulang CPL / Panduan Teknis Evaluasi Kurikulum',
                'hint'        => 'Upload SOP Kaji Ulang CPL atau Panduan Teknis Evaluasi & Pemutakhiran Kurikulum yang berlaku.',
                'bobot'       => 60,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Laporan Evaluasi Pembelajaran / SK Tim Evaluasi Akademik',
                'hint'        => 'Upload laporan evaluasi pembelajaran terbaru atau SK penetapan Tim Evaluasi Akademik.',
                'bobot'       => 40,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K1.3.3: Dokumentasi Rekaman Kaji Ulang CPL ───────────────────────
        // Referensi Borang: Dokumen SPMI, Form Evaluasi CPL, Arsip Tindak Lanjut
        $k = Kriteria::where('kode', 'K1.3.3')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Penjelasan Sistem Dokumentasi Kaji Ulang CPL',
                'hint'        => 'Jelaskan sistem yang digunakan Prodi untuk merekam dan memelihara input, proses, output, dan tindak lanjut kaji ulang CPL.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Dokumen SPMI / Form Evaluasi CPL',
                'hint'        => 'Upload dokumen Sistem Penjamin Mutu Internal (SPMI) atau form evaluasi CPL yang digunakan.',
                'bobot'       => 60,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Arsip Tindak Lanjut Perbaikan Kaji Ulang CPL',
                'hint'        => 'Upload bukti tindak lanjut hasil kaji ulang CPL (risalah rapat, dokumen revisi, atau surat keputusan perbaikan).',
                'bobot'       => 40,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // =====================================================================
        // KRITERIA 2 — IMPLEMENTASI PEMBELAJARAN
        // =====================================================================

        // ── K2.1.1: Cakupan Bidang Topik Kurikulum ───────────────────────────
        // Referensi Borang: Dokumen Kurikulum per Prodi (AE/TRO/TRMO/TRSA)
        $k = Kriteria::where('kode', 'K2.1.1')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Cakupan Bidang Topik Kurikulum',
                'hint'        => 'Paparkan bagaimana kurikulum mencakup: (a) matematika, (b) topik dasar & lanjut computing, (c) pendidikan umum. Sertakan persentase SKS per bidang topik dan kesesuaiannya dengan CPL.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Dokumen Kurikulum Program Studi (Lengkap dengan Struktur Mata Kuliah)',
                'hint'        => 'Upload dokumen kurikulum resmi yang memuat daftar mata kuliah, SKS, dan distribusi per bidang topik.',
                'bobot'       => 70,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'checklist',
                'label'       => 'Kurikulum telah memenuhi ketentuan proporsi bidang topik IABEE (≥50% computing, maks 30% pendidikan umum)',
                'hint'        => 'Centang jika kurikulum sudah memenuhi persyaratan proporsi bidang topik sesuai Kriteria Umum IABEE.',
                'bobot'       => 30,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K2.1.2: Pengembangan Kurikulum Berbasis Pemangku Kepentingan ──────
        // Referensi Borang: SOP Kaji Ulang Kurikulum (SPMI), Tracer Study, FGD
        $k = Kriteria::where('kode', 'K2.1.2')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Proses Pengembangan Kurikulum Berbasis Masukan Pemangku Kepentingan',
                'hint'        => 'Paparkan bagaimana Prodi mengembangkan dan mengkaji ulang kurikulum secara berkala, termasuk mekanisme pelibatan industri, alumni, dan pemangku kepentingan lainnya.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'SOP Kaji Ulang Kurikulum / Dokumen Standar SPMI',
                'hint'        => 'Upload SOP Kaji Ulang Kurikulum atau Dokumen Standar SPMI yang mengatur mekanisme pengembangan kurikulum.',
                'bobot'       => 50,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Laporan/Dokumentasi Tracer Study atau FGD Kurikulum',
                'hint'        => 'Upload hasil tracer study alumni, laporan FGD industri, atau notulensi pertemuan dengan pemangku kepentingan terkait pengembangan kurikulum.',
                'bobot'       => 50,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K2.1.3: Hubungan Struktural Kurikulum dengan CPL ─────────────────
        // Referensi Borang: Dokumen Kurikulum, RPS, Laporan Magang, Website Prodi
        $k = Kriteria::where('kode', 'K2.1.3')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Hubungan Struktural Mata Kuliah dengan CPL',
                'hint'        => 'Paparkan bagaimana setiap mata kuliah berkontribusi terhadap pencapaian CPL. Sertakan peta kurikulum (curriculum map) atau matriks CPL-Mata Kuliah.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Dokumen Kurikulum dengan Matriks CPL-Mata Kuliah',
                'hint'        => 'Upload dokumen kurikulum yang memuat peta/matriks hubungan CPL dengan mata kuliah.',
                'bobot'       => 60,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Contoh Rencana Pembelajaran Semester (RPS)',
                'hint'        => 'Upload contoh RPS yang menunjukkan integrasi CPL dalam perencanaan pembelajaran per mata kuliah.',
                'bobot'       => 40,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K2.1.4: Pengalaman Praktek Computing dalam Kurikulum ─────────────
        // Referensi Borang: RPS, Laporan Kegiatan/Laprak MK
        $k = Kriteria::where('kode', 'K2.1.4')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Pengalaman Praktek Computing dalam Kurikulum',
                'hint'        => 'Paparkan bagaimana kurikulum memberikan kesempatan kepada mahasiswa untuk mengembangkan kompetensi keterampilan computing dalam penerapan praktis, termasuk daftar mata kuliah praktikum/proyek.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'RPS Mata Kuliah Praktikum / Proyek Computing',
                'hint'        => 'Upload RPS mata kuliah yang berfokus pada praktik computing dan penerapan computational thinking.',
                'bobot'       => 60,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Laporan Kegiatan / Laporan Praktikum Mahasiswa',
                'hint'        => 'Upload contoh laporan kegiatan atau laporan praktikum mahasiswa sebagai bukti pelaksanaan.',
                'bobot'       => 40,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K2.2.1: Jumlah, Kualifikasi, dan Kompetensi Dosen ────────────────
        // Referensi Borang: Jajaran Dosen, Kegiatan Pengembangan Dosen, P2ai
        $k = Kriteria::where('kode', 'K2.2.1')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Jumlah, Kualifikasi, dan Kegiatan Pengembangan Dosen',
                'hint'        => 'Paparkan komposisi dosen (jumlah, pendidikan terakhir, jabatan fungsional), pengalaman industri, dan kegiatan pengembangan profesi (sertifikasi, lokakarya, sabatikal, dll.).',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Daftar Jajaran Dosen beserta Kualifikasi dan Bidang Keahlian',
                'hint'        => 'Upload daftar dosen yang memuat nama, pendidikan terakhir, jabatan fungsional, dan bidang keahlian.',
                'bobot'       => 60,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'numerik',
                'label'       => 'Rasio Dosen: Mahasiswa (maks 1:30)',
                'hint'        => 'Masukkan rasio dosen aktif terhadap mahasiswa aktif Prodi (contoh: isi 25 jika rasionya 1:25).',
                'bobot'       => 40,
                'urutan'      => 3,
                'wajib'       => true,
                'nilai_min_numerik' => null,
            ]);
        }

        // ── K2.2.2: Kesadaran Dosen terhadap Relevansi CPL ───────────────────
        // Referensi Borang: SK Satgas Kurikulum, Peraturan Pedoman Kurikulum, Kegiatan Pengembangan
        $k = Kriteria::where('kode', 'K2.2.2')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Mekanisme Penyadaran Dosen terhadap CPL',
                'hint'        => 'Paparkan bagaimana Prodi memastikan dosen memahami perannya dalam pencapaian CPL, termasuk kegiatan workshop kurikulum, rapat koordinasi, dan mekanisme evaluasi dosen.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'SK Satgas Penyusunan Kurikulum Prodi / Peraturan Pedoman Kurikulum',
                'hint'        => 'Upload SK Satgas atau Peraturan Pedoman Kurikulum yang menunjukkan keterlibatan dosen dalam penyusunan dan evaluasi CPL.',
                'bobot'       => 70,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'checklist',
                'label'       => 'Seluruh dosen telah mendapatkan sosialisasi CPL dan peran kontribusinya',
                'hint'        => 'Centang jika semua dosen aktif telah mendapatkan sosialisasi tentang CPL dan kontribusi mata kuliah yang diampunya.',
                'bobot'       => 30,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K2.3.1: Standar Seleksi Mahasiswa ────────────────────────────────
        // Referensi Borang: SK Direktur PMB, SOP PMB, Website PMB
        $k = Kriteria::where('kode', 'K2.3.1')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Kebijakan dan Prosedur Seleksi Mahasiswa',
                'hint'        => 'Paparkan kebijakan dan prosedur penerimaan mahasiswa baru, termasuk persyaratan, proses seleksi, pengakuan kredit transfer, dan penanganan kasus mahasiswa yang tidak memenuhi syarat.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'SK Direktur PMB / SOP Penerimaan Mahasiswa Baru',
                'hint'        => 'Upload SK penetapan PMB atau SOP Penerimaan Mahasiswa Baru yang berlaku.',
                'bobot'       => 70,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'checklist',
                'label'       => 'Terdapat prosedur tertulis untuk penerimaan mahasiswa pindahan dan pengalihan kredit',
                'hint'        => 'Centang jika Prodi memiliki prosedur yang terdokumentasi untuk mahasiswa transfer dan pengakuan kredit.',
                'bobot'       => 30,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K2.3.2: Pemantauan Kemajuan Studi dan Evaluasi Kinerja Mahasiswa ─
        // Referensi Borang: SOP Pemantauan Kinerja, Laporan Kinerja Polman
        $k = Kriteria::where('kode', 'K2.3.2')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Sistem Pemantauan Kemajuan Studi Mahasiswa',
                'hint'        => 'Paparkan kebijakan dan prosedur yang diterapkan Prodi untuk memantau kemajuan akademik dan kinerja mahasiswa secara efektif, termasuk frekuensi dan dokumentasinya.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'SOP Pemantauan Kinerja Mahasiswa / SK Direktur terkait',
                'hint'        => 'Upload SOP atau SK Direktur yang mengatur mekanisme pemantauan kinerja dan kemajuan studi mahasiswa.',
                'bobot'       => 60,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Laporan Kinerja / Rekap Evaluasi Mahasiswa (terbaru)',
                'hint'        => 'Upload laporan kinerja mahasiswa atau rekap evaluasi akademik dari semester/tahun terakhir.',
                'bobot'       => 40,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K2.3.3: Suasana Akademik yang Kondusif ───────────────────────────
        // Referensi Borang: Dokumen Kegiatan Suasana Akademik, Buku Pedoman Penasehatan, Peraturan Akademik
        $k = Kriteria::where('kode', 'K2.3.3')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Kegiatan Pembangunan Suasana Akademik Kondusif',
                'hint'        => 'Paparkan bagaimana Prodi membangun suasana akademik yang kondusif, termasuk layanan bimbingan akademik, konseling, serta kebijakan penasehatan akademik dan karir mahasiswa.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Peraturan Akademik / Buku Pedoman Penasehatan Akademik',
                'hint'        => 'Upload peraturan akademik atau buku pedoman penasehatan/pembimbingan akademik yang berlaku.',
                'bobot'       => 60,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Dokumentasi Kegiatan Peningkatan Suasana Akademik',
                'hint'        => 'Upload dokumentasi kegiatan yang mendukung suasana akademik kondusif (seminar, kuliah tamu, lomba akademik, dll.).',
                'bobot'       => 40,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K2.3.4: Kegiatan Ko-Kurikuler Pembangunan Karakter ───────────────
        // Referensi Borang: Dokumen Kegiatan UKM, ORMAWA, SK ORMAWA
        $k = Kriteria::where('kode', 'K2.3.4')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Kegiatan Ko-Kurikuler dan Pengembangan Soft-Skills',
                'hint'        => 'Paparkan kegiatan ko-kurikuler yang dilaksanakan (UKM, ORMAWA, studium generale, dll.) beserta dampaknya terhadap pengembangan karakter, soft-skills, dan jiwa kewirausahaan mahasiswa.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Dokumentasi Kegiatan UKM / ORMAWA (SK dan Laporan)',
                'hint'        => 'Upload SK ORMAWA atau laporan kegiatan mahasiswa ko-kurikuler yang mendukung pembangunan karakter.',
                'bobot'       => 70,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'checklist',
                'label'       => 'Terdapat program terstruktur untuk pengembangan soft-skills dan kewirausahaan mahasiswa',
                'hint'        => 'Centang jika Prodi memiliki program terstruktur untuk mengembangkan soft-skills dan jiwa kewirausahaan mahasiswa.',
                'bobot'       => 30,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K2.4.1: Ketersediaan dan Kecukupan Fasilitas Pembelajaran ─────────
        // Referensi Borang: SOP Lab, Kebijakan Pemeliharaan, Fasilitas Fisik, Kurikulum
        $k = Kriteria::where('kode', 'K2.4.1')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Ketersediaan dan Kecukupan Fasilitas Pembelajaran',
                'hint'        => 'Paparkan fasilitas fisik Prodi (ruang kelas, laboratorium, sumberdaya komputasi, perpustakaan) beserta asesmen kecukupannya dan panduan keselamatan penggunaan.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Inventaris dan Daftar Fasilitas Fisik Prodi',
                'hint'        => 'Upload daftar inventaris fasilitas lengkap (ruang kelas, lab, peralatan, perangkat lunak, sumberdaya komputasi).',
                'bobot'       => 50,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'SOP Penggunaan Laboratorium / Kebijakan Pemeliharaan Rutin',
                'hint'        => 'Upload SOP penggunaan laboratorium dan/atau kebijakan pemeliharaan rutin fasilitas.',
                'bobot'       => 50,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K2.5.1: Tata Kelola Prodi dan Dukungan Institusi ─────────────────
        // Referensi Borang: SK Pengelolaan Keuangan, Tarif PNBP, RENSTRA
        $k = Kriteria::where('kode', 'K2.5.1')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Tata Kelola dan Dukungan Institusi terhadap Prodi',
                'hint'        => 'Paparkan tata kelola Prodi, kebijakan anggaran institusi, dukungan tenaga kependidikan/staf, dan layanan kelembagaan yang menjamin mutu dan keberlanjutan Prodi.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Dokumen RENSTRA / Kebijakan Pengelolaan Keuangan Prodi',
                'hint'        => 'Upload RENSTRA Institusi atau kebijakan pengelolaan keuangan yang menjamin keberlanjutan Prodi.',
                'bobot'       => 70,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'checklist',
                'label'       => 'Terdapat sistem tata kelola yang menjamin keterlibatan pimpinan dalam pengambilan keputusan Prodi',
                'hint'        => 'Centang jika terdapat mekanisme formal yang melibatkan pimpinan dalam pengambilan keputusan strategis Prodi.',
                'bobot'       => 30,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K2.5.2: Kemitraan dan Kerjasama Tridharma ────────────────────────
        // Referensi Borang: Dokumen Kerjasama, Kurikulum (halaman kerjasama)
        $k = Kriteria::where('kode', 'K2.5.2')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Kemitraan dan Kerjasama dalam Tridharma',
                'hint'        => 'Paparkan kemitraan dengan lembaga eksternal (industri, pusat riset, pemerintah) yang difasilitasi POI untuk mengembangkan Tridharma Perguruan Tinggi dan memanfaatkan sumberdaya lokal.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Dokumen Kerjasama / MoU dengan Mitra Industri/Institusi',
                'hint'        => 'Upload dokumen MoU atau perjanjian kerjasama yang masih aktif dengan mitra industri, penelitian, atau lembaga masyarakat.',
                'bobot'       => 70,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'checklist',
                'label'       => 'Terdapat kerjasama aktif yang mencakup minimal dua aspek Tridharma (pendidikan, penelitian, pengabdian)',
                'hint'        => 'Centang jika kerjasama yang dimiliki mencakup minimal dua dari tiga aspek Tridharma Perguruan Tinggi.',
                'bobot'       => 30,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // =====================================================================
        // KRITERIA 3 — ASESMEN CAPAIAN PEMBELAJARAN
        // =====================================================================

        // ── K3.1.1: Indikator Kinerja dan Metode Asesmen per CPL ─────────────
        // Referensi Borang: RPS, Standar Penilaian, Contoh Instrumen Ujian/Proyek
        $k = Kriteria::where('kode', 'K3.1.1')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Indikator Kinerja dan Metode Asesmen per CPL',
                'hint'        => 'Paparkan indikator kinerja untuk setiap butir CPL (a-e) beserta metode asesmen yang digunakan untuk mengukur ketercapaiannya.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'RPS yang Memuat Indikator CPL / Standar Penilaian Pembelajaran',
                'hint'        => 'Upload RPS atau Standar Penilaian Pembelajaran yang memuat indikator kinerja CPL dan metode asesmennya.',
                'bobot'       => 60,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Contoh Instrumen Asesmen / Rubrik Penilaian',
                'hint'        => 'Upload contoh instrumen ujian, rubrik penilaian proyek, atau alat asesmen lain yang digunakan untuk mengukur CPL.',
                'bobot'       => 40,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K3.1.2: Metode dan Prosedur Pengukuran Pemenuhan CPL ─────────────
        // Referensi Borang: Dokumen SPMI
        $k = Kriteria::where('kode', 'K3.1.2')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Metode dan Prosedur Pengukuran Ketercapaian CPL',
                'hint'        => 'Paparkan secara komprehensif metode dan prosedur yang diterapkan Prodi untuk mengukur pemenuhan CPL secara menyeluruh, termasuk frekuensi pengukuran dan mekanisme dokumentasinya.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Dokumen SPMI / Prosedur Pengukuran CPL yang Terdokumentasi',
                'hint'        => 'Upload dokumen SPMI atau prosedur pengukuran CPL yang rinci, konsisten, dan terdokumentasi secara resmi.',
                'bobot'       => 70,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'checklist',
                'label'       => 'Prosedur pengukuran CPL mencakup seluruh CPL (a-e) dan dilaksanakan secara konsisten',
                'hint'        => 'Centang jika prosedur pengukuran mencakup semua CPL yang ditetapkan dan diimplementasikan secara konsisten setiap semester.',
                'bobot'       => 30,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K3.2.1: Kebijakan dan Prosedur Persyaratan Kelulusan ─────────────
        // Referensi Borang: Buku Panduan Akademik, Peraturan Akademik, Sistem Informasi Akademik
        $k = Kriteria::where('kode', 'K3.2.1')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Kebijakan Persyaratan Kelulusan Mahasiswa',
                'hint'        => 'Paparkan kebijakan dan prosedur yang diterapkan Prodi untuk memastikan setiap mahasiswa memenuhi semua persyaratan kelulusan, termasuk verifikasi SKS, IPK, dan pemenuhan CPL.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Buku Panduan Akademik / Peraturan Akademik yang Memuat Persyaratan Kelulusan',
                'hint'        => 'Upload Buku Panduan Akademik atau Peraturan Akademik yang secara eksplisit memuat persyaratan kelulusan.',
                'bobot'       => 70,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'checklist',
                'label'       => 'Terdapat mekanisme verifikasi pemenuhan semua CPL sebagai syarat kelulusan',
                'hint'        => 'Centang jika ada sistem/prosedur tertulis yang memverifikasi bahwa mahasiswa telah memenuhi seluruh CPL sebelum dinyatakan lulus.',
                'bobot'       => 30,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K3.2.2: Bukti Pencapaian Seluruh CPL oleh Lulusan ────────────────
        // Referensi Borang: Transkrip, Dokumen Audit Kelulusan, Laporan Rekap CPL Lulusan
        $k = Kriteria::where('kode', 'K3.2.2')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Mekanisme Penjaminan CPL Lulusan',
                'hint'        => 'Paparkan bagaimana Prodi memastikan semua CPL telah dicapai oleh seluruh lulusannya, termasuk mekanisme dokumentasi dan penyimpanan rekaman kelulusan.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Laporan Rekap Ketercapaian CPL Lulusan / Dokumen Audit Kelulusan',
                'hint'        => 'Upload laporan rekap ketercapaian CPL lulusan atau dokumen audit kelulusan yang mencantumkan pemenuhan CPL per mahasiswa/angkatan.',
                'bobot'       => 70,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'checklist',
                'label'       => 'Rekaman pencapaian CPL lulusan tersimpan secara terdokumentasi dan dapat diakses',
                'hint'        => 'Centang jika rekaman pemenuhan CPL oleh setiap lulusan tersimpan dengan baik dalam sistem yang terdokumentasi dan dapat diakses sewaktu-waktu.',
                'bobot'       => 30,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // =====================================================================
        // KRITERIA 4 — PERBAIKAN BERKELANJUTAN
        // =====================================================================

        // ── K4.1.1: Analisis dan Evaluasi Periodik Ketercapaian CPL ──────────
        // Referensi Borang: Laporan Ketercapaian CPL, Rekapitulasi Asesmen, Analisis Gap
        $k = Kriteria::where('kode', 'K4.1.1')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Analisis Evaluasi Periodik Ketercapaian CPL',
                'hint'        => 'Jelaskan hasil analisis dan evaluasi periodik pengukuran CPL, yang mencakup identifikasi isu, pemenuhan target kinerja, dan akar masalah yang ditemukan.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Laporan Ketercapaian CPL per Semester/Tahun Akademik',
                'hint'        => 'Upload laporan resmi ketercapaian CPL terbaru yang memuat data pengukuran, target, capaian aktual, dan analisis gap.',
                'bobot'       => 60,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Rekapitulasi Hasil Asesmen CPL per Mata Kuliah',
                'hint'        => 'Upload rekapitulasi hasil asesmen CPL yang dipetakan per mata kuliah untuk mengidentifikasi area yang membutuhkan perbaikan.',
                'bobot'       => 40,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K4.1.2: Penggunaan Hasil Evaluasi untuk Peningkatan Mutu ─────────
        // Referensi Borang: Laporan Ketercapaian CPL, Risalah Rapat, Dokumen Revisi Kurikulum
        $k = Kriteria::where('kode', 'K4.1.2')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Penggunaan Hasil Evaluasi CPL untuk Perbaikan Mutu',
                'hint'        => 'Jelaskan bagaimana hasil evaluasi ketercapaian CPL digunakan sebagai dasar pengambilan keputusan perbaikan mutu (revisi kurikulum, metode pembelajaran, asesmen, atau sumberdaya).',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Risalah Rapat Tinjauan Program Studi / Dokumen Revisi Kurikulum/RPS',
                'hint'        => 'Upload risalah rapat tinjauan Prodi atau dokumen revisi kurikulum/RPS yang dipicu oleh hasil evaluasi CPL.',
                'bobot'       => 70,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'checklist',
                'label'       => 'Terdapat bukti nyata perubahan/perbaikan yang dilakukan berdasarkan hasil evaluasi CPL',
                'hint'        => 'Centang jika ada bukti konkret (revisi RPS, perubahan metode ajar, pengadaan sarana, dll.) yang dilakukan sebagai tindak lanjut evaluasi CPL.',
                'bobot'       => 30,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K4.2.1: Bukti Siklus PDCA Perbaikan Mutu ─────────────────────────
        // Referensi Borang: LTM, Laporan AMI, Risalah Rapat Tinjauan Prodi
        $k = Kriteria::where('kode', 'K4.2.1')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Implementasi dan Evaluasi Siklus PDCA',
                'hint'        => 'Jelaskan bahwa keputusan-keputusan perbaikan mutu telah dilaksanakan (Do), dievaluasi efektivitasnya (Check), dan ditindaklanjuti (Act) sebagai bukti siklus PDCA yang berjalan.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Laporan Tinjauan Manajemen (LTM) / Laporan Audit Mutu Internal (AMI)',
                'hint'        => 'Upload Laporan Tinjauan Manajemen (LTM) atau Laporan Audit Mutu Internal (AMI) yang mencerminkan siklus PDCA.',
                'bobot'       => 60,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Risalah Rapat Tinjauan Program Studi (bukti evaluasi efektivitas)',
                'hint'        => 'Upload risalah rapat tinjauan Prodi yang memuat evaluasi efektivitas perbaikan yang telah dilakukan.',
                'bobot'       => 40,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }

        // ── K4.2.2: Sistem Dokumentasi Tindakan Perbaikan ────────────────────
        // Referensi Borang: Daftar Induk Dokumen Prodi, Laporan Tindak Lanjut, Laporan AMI
        $k = Kriteria::where('kode', 'K4.2.2')->first();
        if ($k) {
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'narasi',
                'label'       => 'Paparan Sistem Dokumentasi Tindakan Perbaikan',
                'hint'        => 'Jelaskan sistem yang digunakan Prodi untuk mendokumentasikan dan memelihara rekaman tindakan perbaikan, termasuk aksesibilitas dokumen tersebut.',
                'bobot'       => 0,
                'urutan'      => 1,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'upload',
                'label'       => 'Daftar Induk Dokumen Program Studi / Laporan Tindak Lanjut Hasil Evaluasi',
                'hint'        => 'Upload Daftar Induk Dokumen Prodi atau Laporan Tindak Lanjut yang membuktikan bahwa rekaman perbaikan terpelihara dan dapat diakses.',
                'bobot'       => 60,
                'urutan'      => 2,
                'wajib'       => true,
            ]);
            TemplateItem::create([
                'kriteria_id' => $k->kriteria_id,
                'tipe'        => 'checklist',
                'label'       => 'Semua dokumen tindakan perbaikan tersimpan dalam sistem terdokumentasi yang dapat diakses pihak berwenang',
                'hint'        => 'Centang jika seluruh rekaman tindakan perbaikan tersimpan dalam sistem manajemen dokumen yang terstruktur, mudah diakses, dan dipelihara secara konsisten.',
                'bobot'       => 40,
                'urutan'      => 3,
                'wajib'       => true,
            ]);
        }
    }
}