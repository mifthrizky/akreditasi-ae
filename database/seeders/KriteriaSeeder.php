<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use Illuminate\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    /**
     * Struktur hierarki kriteria IABEE 2026 sesuai Borang Akreditasi Internasional:
     *
     * Level 0 → Kriteria Utama (1-4), tidak punya template
     * Level 1 → Sub-grup (1.1, 1.2, ..., 4.2), tidak punya template, hanya pengelompokan
     * Level 2 → Sub-kriteria (1.1.1, 1.1.2, ..., 4.2.2), PUNYA template & submission
     *
     * Total sub-kriteria Level 2: 29 item (bobot masing-masing = 1)
     * Distribusi: K1=8, K2=13, K3=4, K4=4
     */
    public function run(): void
    {
        // =====================================================================
        // LEVEL 0 — KRITERIA UTAMA (4 kriteria)
        // Bobot proporsional berdasarkan jumlah sub-kriteria aktif (total 29)
        // K1=8/29≈28, K2=13/29≈45, K3=4/29≈14, K4=4/29≈13
        // =====================================================================

        $k1 = Kriteria::create([
            'kode'      => 'K1',
            'nama'      => 'Orientasi Kompetensi Lulusan',
            'deskripsi' => 'Kriteria 1: Penetapan, penyebaran, dan kaji ulang Profil Profesional Mandiri (PPM) serta Capaian Pembelajaran Prodi (CPL).',
            'level'     => 0,
            'bobot'     => 28,
            'urutan'    => 1,
        ]);

        $k2 = Kriteria::create([
            'kode'      => 'K2',
            'nama'      => 'Implementasi Pembelajaran',
            'deskripsi' => 'Kriteria 2: Penyelenggaraan pembelajaran yang mencakup kurikulum, dosen, mahasiswa, fasilitas, dan tanggung jawab institusi.',
            'level'     => 0,
            'bobot'     => 45,
            'urutan'    => 2,
        ]);

        $k3 = Kriteria::create([
            'kode'      => 'K3',
            'nama'      => 'Asesmen Capaian Pembelajaran',
            'deskripsi' => 'Kriteria 3: Proses pengukuran ketercapaian CPL dan penjaminan kelulusan mahasiswa.',
            'level'     => 0,
            'bobot'     => 14,
            'urutan'    => 3,
        ]);

        $k4 = Kriteria::create([
            'kode'      => 'K4',
            'nama'      => 'Perbaikan Berkelanjutan',
            'deskripsi' => 'Kriteria 4: Evaluasi berkala dan tindak lanjut berbasis hasil asesmen CPL untuk peningkatan mutu Prodi.',
            'level'     => 0,
            'bobot'     => 13,
            'urutan'    => 4,
        ]);

        // =====================================================================
        // LEVEL 1 — SUB-GRUP (di bawah K1: 3 grup; K2: 5 grup; K3: 2 grup; K4: 2 grup)
        // Bobot = jumlah sub-kriteria Level 2 di dalamnya
        // =====================================================================

        // --- Sub-grup K1 ---
        $k1_1 = Kriteria::create([
            'parent_id' => $k1->kriteria_id,
            'kode'      => 'K1.1',
            'nama'      => 'Penetapan Profil Profesional Mandiri (PPM)',
            'deskripsi' => 'Prodi menetapkan profil lulusan yang digagaskan sebagai Profesional Mandiri, dengan mempertimbangkan potensi sumberdaya, budaya, dan kebutuhan Negara.',
            'level'     => 1,
            'bobot'     => 2,
            'urutan'    => 1,
        ]);

        $k1_2 = Kriteria::create([
            'parent_id' => $k1->kriteria_id,
            'kode'      => 'K1.2',
            'nama'      => 'Penyebaran PPM dan Penetapan CPL',
            'deskripsi' => 'Prodi menginformasikan PPM kepada mahasiswa, dosen, dan masyarakat umum, serta menetapkan Capaian Pembelajaran Prodi (CPL).',
            'level'     => 1,
            'bobot'     => 3,
            'urutan'    => 2,
        ]);

        $k1_3 = Kriteria::create([
            'parent_id' => $k1->kriteria_id,
            'kode'      => 'K1.3',
            'nama'      => 'Publikasi dan Kaji Ulang CPL',
            'deskripsi' => 'Program mempublikasikan PPM dan CPL secara luas serta menetapkan kebijakan kaji ulang berkala yang ditindaklanjuti secara konsisten.',
            'level'     => 1,
            'bobot'     => 3,
            'urutan'    => 3,
        ]);

        // --- Sub-grup K2 ---
        $k2_1 = Kriteria::create([
            'parent_id' => $k2->kriteria_id,
            'kode'      => 'K2.1',
            'nama'      => 'Kurikulum',
            'deskripsi' => 'Kurikulum Prodi mencakup bidang-bidang topik yang dipersyaratkan IABEE dan disiapkan secara sistematis untuk mendukung pencapaian CPL.',
            'level'     => 1,
            'bobot'     => 4,
            'urutan'    => 1,
        ]);

        $k2_2 = Kriteria::create([
            'parent_id' => $k2->kriteria_id,
            'kode'      => 'K2.2',
            'nama'      => 'Dosen (Faculty)',
            'deskripsi' => 'Prodi menyediakan jajaran dosen dengan jumlah, kualifikasi, dan kompetensi yang memadai untuk menyelenggarakan pembelajaran yang efektif.',
            'level'     => 1,
            'bobot'     => 2,
            'urutan'    => 2,
        ]);

        $k2_3 = Kriteria::create([
            'parent_id' => $k2->kriteria_id,
            'kode'      => 'K2.3',
            'nama'      => 'Mahasiswa dan Suasana Akademik',
            'deskripsi' => 'Prodi menetapkan standar seleksi, pemantauan kemajuan studi, dan membangun suasana akademik yang kondusif bagi pembelajaran.',
            'level'     => 1,
            'bobot'     => 4,
            'urutan'    => 3,
        ]);

        $k2_4 = Kriteria::create([
            'parent_id' => $k2->kriteria_id,
            'kode'      => 'K2.4',
            'nama'      => 'Fasilitas',
            'deskripsi' => 'Prodi menjamin ketersediaan, aksesibilitas, dan keselamatan fasilitas demi berjalannya proses pembelajaran yang efektif.',
            'level'     => 1,
            'bobot'     => 1,
            'urutan'    => 4,
        ]);

        $k2_5 = Kriteria::create([
            'parent_id' => $k2->kriteria_id,
            'kode'      => 'K2.5',
            'nama'      => 'Tanggung Jawab Institusi',
            'deskripsi' => 'Institusi Pengelola Prodi (POI) mengelola penyediaan layanan pendidikan, sumberdaya, dan kerjasama dengan pemangku kepentingan.',
            'level'     => 1,
            'bobot'     => 2,
            'urutan'    => 5,
        ]);

        // --- Sub-grup K3 ---
        $k3_1 = Kriteria::create([
            'parent_id' => $k3->kriteria_id,
            'kode'      => 'K3.1',
            'nama'      => 'Proses Asesmen CPL',
            'deskripsi' => 'Prodi menjalankan proses asesmen CPL berdasarkan indikator kinerja yang rinci pada interval waktu yang terencana.',
            'level'     => 1,
            'bobot'     => 2,
            'urutan'    => 1,
        ]);

        $k3_2 = Kriteria::create([
            'parent_id' => $k3->kriteria_id,
            'kode'      => 'K3.2',
            'nama'      => 'Jaminan Pemenuhan CPL oleh Lulusan',
            'deskripsi' => 'Prodi menjamin bahwa setiap lulusannya telah memenuhi seluruh Capaian Pembelajaran Prodi yang diharapkan.',
            'level'     => 1,
            'bobot'     => 2,
            'urutan'    => 2,
        ]);

        // --- Sub-grup K4 ---
        $k4_1 = Kriteria::create([
            'parent_id' => $k4->kriteria_id,
            'kode'      => 'K4.1',
            'nama'      => 'Evaluasi Berkala Hasil Asesmen CPL',
            'deskripsi' => 'Prodi melaksanakan evaluasi berkala dalam interval yang terencana berdasarkan hasil asesmen CPL untuk meningkatkan efektivitas pembelajaran.',
            'level'     => 1,
            'bobot'     => 2,
            'urutan'    => 1,
        ]);

        $k4_2 = Kriteria::create([
            'parent_id' => $k4->kriteria_id,
            'kode'      => 'K4.2',
            'nama'      => 'Pemeliharaan Dokumen dan Rekaman Evaluasi',
            'deskripsi' => 'Prodi memelihara dokumen dan rekaman terkait pelaksanaan evaluasi, hasil-hasil yang diperoleh, serta tindak lanjutnya.',
            'level'     => 1,
            'bobot'     => 2,
            'urutan'    => 2,
        ]);

        // =====================================================================
        // LEVEL 2 — SUB-KRITERIA (29 item, bobot masing-masing = 1)
        // Ini adalah unit terkecil yang memiliki template & submission
        // =====================================================================

        // ── K1.1: Penetapan PPM ──────────────────────────────────────────────

        Kriteria::create([
            'parent_id' => $k1_1->kriteria_id,
            'kode'      => 'K1.1.1',
            'nama'      => 'Profil Profesional Mandiri (PPM) Prodi',
            'deskripsi' => 'Paparkan Profil Profesional Mandiri Prodi yang ditetapkan sebagai sasaran kependidikan, mempertimbangkan sumberdaya, kearifan, kebutuhan lokal/nasional, dan misi Institusi (POI).',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 1,
        ]);

        Kriteria::create([
            'parent_id' => $k1_1->kriteria_id,
            'kode'      => 'K1.1.2',
            'nama'      => 'Proses Penyusunan dan Kaji Ulang PPM',
            'deskripsi' => 'Paparkan proses yang diselenggarakan Prodi untuk menyusun dan mengkaji ulang secara berkala PPM, dengan melibatkan para pemangku kepentingan Prodi.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 2,
        ]);

        // ── K1.2: Penyebaran PPM dan CPL ────────────────────────────────────

        Kriteria::create([
            'parent_id' => $k1_2->kriteria_id,
            'kode'      => 'K1.2.1',
            'nama'      => 'Penyebarluasan PPM kepada Sivitas Akademika',
            'deskripsi' => 'Jelaskan bagaimana Prodi menyebarluaskan PPM secara memadai kepada mahasiswa, dosen, dan masyarakat umum.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 1,
        ]);

        Kriteria::create([
            'parent_id' => $k1_2->kriteria_id,
            'kode'      => 'K1.2.2',
            'nama'      => 'Capaian Pembelajaran Prodi (CPL)',
            'deskripsi' => 'Prodi menetapkan CPL yang terdiri dari kemampuan (a) analisis computing kompleks, (b) desain solusi computing, (c) rancangan sistem rekayasa, (d) tanggung jawab profesional dan etika, serta (e) kemampuan kerja tim/kepemimpinan.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 2,
        ]);

        Kriteria::create([
            'parent_id' => $k1_2->kriteria_id,
            'kode'      => 'K1.2.3',
            'nama'      => 'Cakupan CPL Tambahan dari Kriteria Disiplin',
            'deskripsi' => 'Jelaskan apakah CPL telah mencakup capaian pembelajaran tambahan yang diminta oleh Kriteria Disiplin yang relevan (bila ada).',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 3,
        ]);

        // ── K1.3: Publikasi dan Kaji Ulang CPL ──────────────────────────────

        Kriteria::create([
            'parent_id' => $k1_3->kriteria_id,
            'kode'      => 'K1.3.1',
            'nama'      => 'Penyebarluasan PPM dan CPL kepada Publik',
            'deskripsi' => 'Jelaskan bagaimana Program menyebarluaskan PPM dan CPL secara efektif kepada calon mahasiswa, mahasiswa, dosen, dan masyarakat umum.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 1,
        ]);

        Kriteria::create([
            'parent_id' => $k1_3->kriteria_id,
            'kode'      => 'K1.3.2',
            'nama'      => 'Prosedur Kaji Ulang Berkala CPL',
            'deskripsi' => 'Prodi menetapkan prosedur untuk melaksanakan kaji ulang berkala terhadap CPL. Paparkan bagaimana Prodi mengkomunikasikan CPL dan proses yang diterapkan untuk kaji ulang berkala.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 2,
        ]);

        Kriteria::create([
            'parent_id' => $k1_3->kriteria_id,
            'kode'      => 'K1.3.3',
            'nama'      => 'Dokumentasi Rekaman Kaji Ulang CPL',
            'deskripsi' => 'Berikan bukti bahwa Program merekam dan memelihara input, proses, output, dan tindak lanjut kaji ulang dalam suatu sistem yang terdokumentasi.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 3,
        ]);

        // ── K2.1: Kurikulum ──────────────────────────────────────────────────

        Kriteria::create([
            'parent_id' => $k2_1->kriteria_id,
            'kode'      => 'K2.1.1',
            'nama'      => 'Cakupan Bidang Topik Kurikulum',
            'deskripsi' => 'Kurikulum harus mencakup: (a) Matematika, (b) Topik dasar dan lanjut computing, (c) Pendidikan umum (moral, etika, sosial-budaya, lingkungan, manajemen). Paparkan bagaimana kurikulum memenuhi cakupan dan alokasi beban yang memadai, konsisten dengan CPL.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 1,
        ]);

        Kriteria::create([
            'parent_id' => $k2_1->kriteria_id,
            'kode'      => 'K2.1.2',
            'nama'      => 'Pengembangan Kurikulum Berbasis Pemangku Kepentingan',
            'deskripsi' => 'Pengembangan kurikulum hendaknya mempertimbangkan masukan dari para pemangku kepentingan Prodi. Paparkan bagaimana Prodi mengembangkan dan mengkaji ulang kurikulum secara berkala melalui kebijakan dan prosedur yang terdokumentasikan, sistematik, dan efektif.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 2,
        ]);

        Kriteria::create([
            'parent_id' => $k2_1->kriteria_id,
            'kode'      => 'K2.1.3',
            'nama'      => 'Hubungan Struktural Kurikulum dengan CPL',
            'deskripsi' => 'Kurikulum harus menunjukkan hubungan struktural dan kontribusi masing-masing mata kuliah dalam membangun CPL. Prosedur, mencakup silabus, ditetapkan dan didokumentasikan sehingga proses pembelajaran dapat diimplementasikan secara terkendali.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 3,
        ]);

        Kriteria::create([
            'parent_id' => $k2_1->kriteria_id,
            'kode'      => 'K2.1.4',
            'nama'      => 'Pengalaman Praktek Computing dalam Kurikulum',
            'deskripsi' => 'Kurikulum harus disiapkan untuk memastikan mahasiswa memperoleh pengalaman praktek computing dan penyelesaian persoalan berdasar algorithm/computational thinking. Paparkan bagaimana kurikulum memberikan kesempatan mengembangkan kompetensi keterampilan computing dalam penerapan praktis.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 4,
        ]);

        // ── K2.2: Dosen ──────────────────────────────────────────────────────

        Kriteria::create([
            'parent_id' => $k2_2->kriteria_id,
            'kode'      => 'K2.2.1',
            'nama'      => 'Jumlah, Kualifikasi, dan Kompetensi Dosen',
            'deskripsi' => 'Prodi hendaknya menyediakan jajaran dosen dengan jumlah, kualifikasi, dan kompetensi yang memadai untuk menyelenggarakan proses pembelajaran dan menjamin penguasaan CPL oleh mahasiswa. Paparkan komposisi, kualifikasi, pengalaman, serta kegiatan pengembangan profesi dosen.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 1,
        ]);

        Kriteria::create([
            'parent_id' => $k2_2->kriteria_id,
            'kode'      => 'K2.2.2',
            'nama'      => 'Kesadaran Dosen terhadap Relevansi CPL',
            'deskripsi' => 'Prodi menjamin bahwa para dosen sadar tentang relevansi dan kepentingan peran serta kontribusi mereka terhadap CPL. Paparkan peran dosen dalam penciptaan, perbaikan, dan evaluasi mata kuliah, serta kebijakan pengembangan dan evaluasi kegiatan akademik dosen.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 2,
        ]);

        // ── K2.3: Mahasiswa dan Suasana Akademik ─────────────────────────────

        Kriteria::create([
            'parent_id' => $k2_3->kriteria_id,
            'kode'      => 'K2.3.1',
            'nama'      => 'Standar Seleksi Mahasiswa',
            'deskripsi' => 'Prodi menetapkan dan menjalankan standar seleksi untuk mahasiswa baru maupun pindahan, serta pengalihan atau pengakuan kredit. Paparkan kebijakan dan prosedur penerimaan mahasiswa, termasuk penetapan persyaratan, proses seleksi, dan penanganan transfer kredit.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 1,
        ]);

        Kriteria::create([
            'parent_id' => $k2_3->kriteria_id,
            'kode'      => 'K2.3.2',
            'nama'      => 'Pemantauan Kemajuan Studi dan Evaluasi Kinerja Mahasiswa',
            'deskripsi' => 'Prodi menetapkan dan menjalankan pemantauan kemajuan studi dan evaluasi kinerja mahasiswa. Prosedur penjaminan mutu ditetapkan untuk memastikan kecukupan standar tercapai dalam semua asesmen.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 2,
        ]);

        Kriteria::create([
            'parent_id' => $k2_3->kriteria_id,
            'kode'      => 'K2.3.3',
            'nama'      => 'Suasana Akademik yang Kondusif',
            'deskripsi' => 'Prodi membangun dan memelihara suasana akademik yang kondusif bagi pembelajaran yang berhasil. Paparkan bagaimana Prodi menyelenggarakan layanan pembimbingan dan konseling akademik/non-akademik, serta kebijakan penasehatan akademik dan karir mahasiswa.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 3,
        ]);

        Kriteria::create([
            'parent_id' => $k2_3->kriteria_id,
            'kode'      => 'K2.3.4',
            'nama'      => 'Kegiatan Ko-Kurikuler Pembangunan Karakter',
            'deskripsi' => 'Prodi mendorong kegiatan ko-kurikuler untuk membangun karakter dan meningkatkan kesadaran mahasiswa tentang kebutuhan negerinya. Paparkan bagaimana Prodi mewujudkan kegiatan yang meningkatkan soft-skills mahasiswa, termasuk kewirausahaan.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 4,
        ]);

        // ── K2.4: Fasilitas ──────────────────────────────────────────────────

        Kriteria::create([
            'parent_id' => $k2_4->kriteria_id,
            'kode'      => 'K2.4.1',
            'nama'      => 'Ketersediaan dan Kecukupan Fasilitas Pembelajaran',
            'deskripsi' => 'Prodi menjamin ketersediaan, aksesibilitas, dan keselamatan fasilitas demi berjalannya proses pembelajaran yang efektif. Paparkan fasilitas fisik (ruang kelas, laboratorium, sumberdaya komputasi, perpustakaan) beserta asesmen kecukupan dan kebijakan pemeliharaannya.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 1,
        ]);

        // ── K2.5: Tanggung Jawab Institusi ───────────────────────────────────

        Kriteria::create([
            'parent_id' => $k2_5->kriteria_id,
            'kode'      => 'K2.5.1',
            'nama'      => 'Tata Kelola Prodi dan Dukungan Institusi',
            'deskripsi' => 'Prodi menetapkan dan mengelola proses penyediaan layanan pendidikan, mencakup perancangan pendidikan, pengembangan dan pelaksanaan kurikulum, serta asesmen pembelajaran. Paparkan tata kelola Prodi, kebijakan anggaran, dan dukungan tenaga kependidikan.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 1,
        ]);

        Kriteria::create([
            'parent_id' => $k2_5->kriteria_id,
            'kode'      => 'K2.5.2',
            'nama'      => 'Kemitraan dan Kerjasama Tridharma',
            'deskripsi' => 'Institusi Pengelola Prodi melaksanakan upaya mengalokasikan sumberdaya, layanan pendukung, dan kerjasama dengan pemangku kepentingan dalam bidang pendidikan, penelitian, dan pengabdian kepada masyarakat, dengan mempertimbangkan sumberdaya lokal.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 2,
        ]);

        // ── K3.1: Proses Asesmen CPL ─────────────────────────────────────────

        Kriteria::create([
            'parent_id' => $k3_1->kriteria_id,
            'kode'      => 'K3.1.1',
            'nama'      => 'Indikator Kinerja dan Metode Asesmen per CPL',
            'deskripsi' => 'Paparkan indikator-indikator kinerja yang ditetapkan Prodi untuk setiap butir CPL, dan metode asesmen yang tepat sebagai dasar untuk mengukur ketercapaian indikator-indikator kinerja tersebut oleh para mahasiswa.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 1,
        ]);

        Kriteria::create([
            'parent_id' => $k3_1->kriteria_id,
            'kode'      => 'K3.1.2',
            'nama'      => 'Metode dan Prosedur Pengukuran Pemenuhan CPL',
            'deskripsi' => 'Paparkan metode dan prosedur pengukuran pemenuhan CPL yang diterapkan oleh Prodi secara komprehensif, rinci, dan terdokumentasi secara konsisten.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 2,
        ]);

        // ── K3.2: Jaminan Pemenuhan CPL oleh Lulusan ─────────────────────────

        Kriteria::create([
            'parent_id' => $k3_2->kriteria_id,
            'kode'      => 'K3.2.1',
            'nama'      => 'Kebijakan dan Prosedur Persyaratan Kelulusan',
            'deskripsi' => 'Paparkan kebijakan dan prosedur yang diterapkan Prodi untuk secara efektif memastikan pemenuhan semua persyaratan kelulusan oleh para lulusannya.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 1,
        ]);

        Kriteria::create([
            'parent_id' => $k3_2->kriteria_id,
            'kode'      => 'K3.2.2',
            'nama'      => 'Bukti Pencapaian Seluruh CPL oleh Lulusan',
            'deskripsi' => 'Paparkan bagaimana Prodi memastikan bahwa semua CPL telah dicapai oleh semua lulusannya. Proses dan hasil kaji ulang persyaratan kelulusan terdokumentasi secara resmi dan disimpan sebagai rekaman tetap.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 2,
        ]);

        // ── K4.1: Evaluasi Berkala ───────────────────────────────────────────

        Kriteria::create([
            'parent_id' => $k4_1->kriteria_id,
            'kode'      => 'K4.1.1',
            'nama'      => 'Analisis dan Evaluasi Periodik Ketercapaian CPL',
            'deskripsi' => 'Jelaskan analisis dan evaluasi periodik terhadap hasil pengukuran CPL yang mencakup identifikasi isu, pemenuhan target kinerja, dan akar masalah, disertai dengan bukti-bukti pendukung.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 1,
        ]);

        Kriteria::create([
            'parent_id' => $k4_1->kriteria_id,
            'kode'      => 'K4.1.2',
            'nama'      => 'Penggunaan Hasil Evaluasi untuk Peningkatan Mutu',
            'deskripsi' => 'Jelaskan bagaimana hasil evaluasi ketercapaian CPL digunakan Program untuk mengambil keputusan-keputusan peningkatan mutu dan kinerja secara berkelanjutan, misalnya terkait capaian pembelajaran, kurikulum, metode pembelajaran, asesmen, dan sumber daya.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 2,
        ]);

        // ── K4.2: Pemeliharaan Dokumen Evaluasi ──────────────────────────────

        Kriteria::create([
            'parent_id' => $k4_2->kriteria_id,
            'kode'      => 'K4.2.1',
            'nama'      => 'Bukti Siklus PDCA Perbaikan Mutu',
            'deskripsi' => 'Jelaskan bahwa keputusan-keputusan perbaikan mutu berkelanjutan telah dilaksanakan dan dievaluasi efektivitasnya sebagai bukti siklus PDCA telah berjalan.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 1,
        ]);

        Kriteria::create([
            'parent_id' => $k4_2->kriteria_id,
            'kode'      => 'K4.2.2',
            'nama'      => 'Sistem Dokumentasi Tindakan Perbaikan',
            'deskripsi' => 'Berikan bukti bahwa implementasi keputusan-keputusan tindakan perbaikan dan hasil evaluasi efektivitasnya terpelihara dalam suatu sistem terdokumentasi dan dapat diakses.',
            'level'     => 2,
            'bobot'     => 1,
            'urutan'    => 2,
        ]);
    }
}