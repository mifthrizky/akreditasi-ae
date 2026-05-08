<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KriteriaTemplateExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize
{
    /**
     * Template data based on KriteriaSeeder structure
     * Format: [kode, nama, deskripsi, level, bobot, urutan, parent_kode]
     */
    public function array(): array
    {
        return [
            // Level 0 - Kriteria Utama
            ['K1', 'Orientasi Kompetensi Lulusan', 'Kriteria 1: Penetapan, penyebaran, dan kaji ulang Profil Profesional Mandiri (PPM) serta Capaian Pembelajaran Prodi (CPL).', 0, 28, 1, ''],
            ['K2', 'Implementasi Pembelajaran', 'Kriteria 2: Penyelenggaraan pembelajaran yang mencakup kurikulum, dosen, mahasiswa, fasilitas, dan tanggung jawab institusi.', 0, 45, 2, ''],
            ['K3', 'Asesmen Capaian Pembelajaran', 'Kriteria 3: Proses pengukuran ketercapaian CPL dan penjaminan kelulusan mahasiswa.', 0, 14, 3, ''],
            ['K4', 'Perbaikan Berkelanjutan', 'Kriteria 4: Evaluasi berkala dan tindak lanjut berbasis hasil asesmen CPL untuk peningkatan mutu Prodi.', 0, 13, 4, ''],
            
            // Level 1 - Sub-grup K1
            ['K1.1', 'Penetapan Profil Profesional Mandiri (PPM)', 'Prodi menetapkan profil lulusan yang digagaskan sebagai Profesional Mandiri, dengan mempertimbangkan potensi sumberdaya, budaya, dan kebutuhan Negara.', 1, 2, 1, 'K1'],
            ['K1.2', 'Penyebaran PPM dan Penetapan CPL', 'Prodi menginformasikan PPM kepada mahasiswa, dosen, dan masyarakat umum, serta menetapkan Capaian Pembelajaran Prodi (CPL).', 1, 3, 2, 'K1'],
            ['K1.3', 'Publikasi dan Kaji Ulang CPL', 'Program mempublikasikan PPM dan CPL secara luas serta menetapkan kebijakan kaji ulang berkala yang ditindaklanjuti secara konsisten.', 1, 3, 3, 'K1'],
            
            // Level 1 - Sub-grup K2
            ['K2.1', 'Kurikulum', 'Kurikulum Prodi mencakup bidang-bidang topik yang dipersyaratkan IABEE dan disiapkan secara sistematis untuk mendukung pencapaian CPL.', 1, 4, 1, 'K2'],
            ['K2.2', 'Dosen (Faculty)', 'Prodi menyediakan jajaran dosen dengan jumlah, kualifikasi, dan kompetensi yang memadai untuk menyelenggarakan pembelajaran yang efektif.', 1, 2, 2, 'K2'],
            ['K2.3', 'Mahasiswa dan Suasana Akademik', 'Prodi menetapkan standar seleksi, pemantauan kemajuan studi, dan membangun suasana akademik yang kondusif bagi pembelajaran.', 1, 4, 3, 'K2'],
            ['K2.4', 'Fasilitas', 'Prodi menjamin ketersediaan, aksesibilitas, dan keselamatan fasilitas demi berjalannya proses pembelajaran yang efektif.', 1, 1, 4, 'K2'],
            ['K2.5', 'Tanggung Jawab Institusi', 'Institusi Pengelola Prodi (POI) mengelola penyediaan layanan pendidikan, sumberdaya, dan kerjasama dengan pemangku kepentingan.', 1, 2, 5, 'K2'],
            
            // Level 1 - Sub-grup K3
            ['K3.1', 'Proses Asesmen CPL', 'Prodi menjalankan proses asesmen CPL berdasarkan indikator kinerja yang rinci pada interval waktu yang terencana.', 1, 2, 1, 'K3'],
            ['K3.2', 'Jaminan Pemenuhan CPL oleh Lulusan', 'Prodi menjamin bahwa setiap lulusannya telah memenuhi seluruh Capaian Pembelajaran Prodi yang diharapkan.', 1, 2, 2, 'K3'],
            
            // Level 1 - Sub-grup K4
            ['K4.1', 'Evaluasi Berkala Hasil Asesmen CPL', 'Prodi melaksanakan evaluasi berkala dalam interval yang terencana berdasarkan hasil asesmen CPL untuk meningkatkan efektivitas pembelajaran.', 1, 2, 1, 'K4'],
            ['K4.2', 'Pemeliharaan Dokumen dan Rekaman Evaluasi', 'Prodi memelihara dokumen dan rekaman terkait pelaksanaan evaluasi, hasil-hasil yang diperoleh, serta tindak lanjutnya.', 1, 2, 2, 'K4'],
            
            // Level 2 - Sub-kriteria (29 items total)
            // K1.1
            ['K1.1.1', 'Profil Profesional Mandiri (PPM) Prodi', 'Paparkan Profil Profesional Mandiri Prodi yang ditetapkan sebagai sasaran kependidikan, mempertimbangkan sumberdaya, kearifan, kebutuhan lokal/nasional, dan misi Institusi (POI).', 2, 1, 1, 'K1.1'],
            ['K1.1.2', 'Proses Penyusunan dan Kaji Ulang PPM', 'Paparkan proses yang diselenggarakan Prodi untuk menyusun dan mengkaji ulang secara berkala PPM, dengan melibatkan para pemangku kepentingan Prodi.', 2, 1, 2, 'K1.1'],
            
            // K1.2
            ['K1.2.1', 'Penyebarluasan PPM kepada Sivitas Akademika', 'Jelaskan bagaimana Prodi menyebarluaskan PPM secara memadai kepada mahasiswa, dosen, dan masyarakat umum.', 2, 1, 1, 'K1.2'],
            ['K1.2.2', 'Capaian Pembelajaran Prodi (CPL)', 'Prodi menetapkan CPL yang terdiri dari kemampuan (a) analisis computing kompleks, (b) desain solusi computing, (c) rancangan sistem rekayasa, (d) tanggung jawab profesional dan etika, serta (e) kemampuan kerja tim/kepemimpinan.', 2, 1, 2, 'K1.2'],
            ['K1.2.3', 'Cakupan CPL Tambahan dari Kriteria Disiplin', 'Jelaskan apakah CPL telah mencakup capaian pembelajaran tambahan yang diminta oleh Kriteria Disiplin yang relevan (bila ada).', 2, 1, 3, 'K1.2'],
            
            // K1.3
            ['K1.3.1', 'Penyebarluasan PPM dan CPL kepada Publik', 'Jelaskan bagaimana Program menyebarluaskan PPM dan CPL secara efektif kepada calon mahasiswa, mahasiswa, dosen, dan masyarakat umum.', 2, 1, 1, 'K1.3'],
            ['K1.3.2', 'Prosedur Kaji Ulang Berkala CPL', 'Prodi menetapkan prosedur untuk melaksanakan kaji ulang berkala terhadap CPL. Paparkan bagaimana Prodi mengkomunikasikan CPL dan proses yang diterapkan untuk kaji ulang berkala.', 2, 1, 2, 'K1.3'],
            ['K1.3.3', 'Dokumentasi Rekaman Kaji Ulang CPL', 'Berikan bukti bahwa Program merekam dan memelihara input, proses, output, dan tindak lanjut kaji ulang dalam suatu sistem yang terdokumentasi.', 2, 1, 3, 'K1.3'],
            
            // K2.1
            ['K2.1.1', 'Cakupan Bidang Topik Kurikulum', 'Kurikulum harus mencakup: (a) Matematika, (b) Topik dasar dan lanjut computing, (c) Pendidikan umum. Paparkan bagaimana kurikulum memenuhi cakupan dan alokasi beban yang memadai, konsisten dengan CPL.', 2, 1, 1, 'K2.1'],
            ['K2.1.2', 'Pengembangan Kurikulum Berbasis Pemangku Kepentingan', 'Pengembangan kurikulum hendaknya mempertimbangkan masukan dari para pemangku kepentingan Prodi. Paparkan bagaimana Prodi mengembangkan dan mengkaji ulang kurikulum secara berkala melalui kebijakan dan prosedur yang terdokumentasikan, sistematik, dan efektif.', 2, 1, 2, 'K2.1'],
            ['K2.1.3', 'Hubungan Struktural Kurikulum dengan CPL', 'Kurikulum harus menunjukkan hubungan struktural dan kontribusi masing-masing mata kuliah dalam membangun CPL. Prosedur, mencakup silabus, ditetapkan dan didokumentasikan sehingga proses pembelajaran dapat diimplementasikan secara terkendali.', 2, 1, 3, 'K2.1'],
            ['K2.1.4', 'Pengalaman Praktek Computing dalam Kurikulum', 'Kurikulum harus disiapkan untuk memastikan mahasiswa memperoleh pengalaman praktek computing dan penyelesaian persoalan berdasar algorithm/computational thinking. Paparkan bagaimana kurikulum memberikan kesempatan mengembangkan kompetensi keterampilan computing dalam penerapan praktis.', 2, 1, 4, 'K2.1'],
            
            // K2.2
            ['K2.2.1', 'Jumlah, Kualifikasi, dan Kompetensi Dosen', 'Prodi hendaknya menyediakan jajaran dosen dengan jumlah, kualifikasi, dan kompetensi yang memadai untuk menyelenggarakan proses pembelajaran dan menjamin penguasaan CPL oleh mahasiswa. Paparkan komposisi, kualifikasi, pengalaman, serta kegiatan pengembangan profesi dosen.', 2, 1, 1, 'K2.2'],
            ['K2.2.2', 'Kesadaran Dosen terhadap Relevansi CPL', 'Prodi menjamin bahwa para dosen sadar tentang relevansi dan kepentingan peran serta kontribusi mereka terhadap CPL. Paparkan peran dosen dalam penciptaan, perbaikan, dan evaluasi mata kuliah, serta kebijakan pengembangan dan evaluasi kegiatan akademik dosen.', 2, 1, 2, 'K2.2'],
            
            // K2.3
            ['K2.3.1', 'Standar Seleksi Mahasiswa', 'Prodi menetapkan dan menjalankan standar seleksi untuk mahasiswa baru maupun pindahan, serta pengalihan atau pengakuan kredit. Paparkan kebijakan dan prosedur penerimaan mahasiswa, termasuk penetapan persyaratan, proses seleksi, dan penanganan transfer kredit.', 2, 1, 1, 'K2.3'],
            ['K2.3.2', 'Pemantauan Kemajuan Studi dan Evaluasi Kinerja Mahasiswa', 'Prodi menetapkan dan menjalankan pemantauan kemajuan studi dan evaluasi kinerja mahasiswa. Prosedur penjaminan mutu ditetapkan untuk memastikan kecukupan standar tercapai dalam semua asesmen.', 2, 1, 2, 'K2.3'],
            ['K2.3.3', 'Suasana Akademik yang Kondusif', 'Prodi membangun dan memelihara suasana akademik yang kondusif bagi pembelajaran yang berhasil. Paparkan bagaimana Prodi menyelenggarakan layanan pembimbingan dan konseling akademik/non-akademik, serta kebijakan penasehatan akademik dan karir mahasiswa.', 2, 1, 3, 'K2.3'],
            ['K2.3.4', 'Kegiatan Ko-Kurikuler Pembangunan Karakter', 'Prodi mendorong kegiatan ko-kurikuler untuk membangun karakter dan meningkatkan kesadaran mahasiswa tentang kebutuhan negerinya. Paparkan bagaimana Prodi mewujudkan kegiatan yang meningkatkan soft-skills mahasiswa, termasuk kewirausahaan.', 2, 1, 4, 'K2.3'],
            
            // K2.4
            ['K2.4.1', 'Ketersediaan dan Kecukupan Fasilitas Pembelajaran', 'Prodi menjamin ketersediaan, aksesibilitas, dan keselamatan fasilitas demi berjalannya proses pembelajaran yang efektif. Paparkan fasilitas fisik (ruang kelas, laboratorium, sumberdaya komputasi, perpustakaan) beserta asesmen kecukupan dan kebijakan pemeliharaannya.', 2, 1, 1, 'K2.4'],
            
            // K2.5
            ['K2.5.1', 'Tata Kelola Prodi dan Dukungan Institusi', 'Prodi menetapkan dan mengelola proses penyediaan layanan pendidikan, mencakup perancangan pendidikan, pengembangan dan pelaksanaan kurikulum, serta asesmen pembelajaran. Paparkan tata kelola Prodi, kebijakan anggaran, dan dukungan tenaga kependidikan.', 2, 1, 1, 'K2.5'],
            ['K2.5.2', 'Kemitraan dan Kerjasama Tridharma', 'Institusi Pengelola Prodi melaksanakan upaya mengalokasikan sumberdaya, layanan pendukung, dan kerjasama dengan pemangku kepentingan dalam bidang pendidikan, penelitian, dan pengabdian kepada masyarakat, dengan mempertimbangkan sumberdaya lokal.', 2, 1, 2, 'K2.5'],
            
            // K3.1
            ['K3.1.1', 'Indikator Kinerja dan Metode Asesmen per CPL', 'Paparkan indikator-indikator kinerja yang ditetapkan Prodi untuk setiap butir CPL, dan metode asesmen yang tepat sebagai dasar untuk mengukur ketercapaian indikator-indikator kinerja tersebut oleh para mahasiswa.', 2, 1, 1, 'K3.1'],
            ['K3.1.2', 'Metode dan Prosedur Pengukuran Pemenuhan CPL', 'Paparkan metode dan prosedur pengukuran pemenuhan CPL yang diterapkan oleh Prodi secara komprehensif, rinci, dan terdokumentasi secara konsisten.', 2, 1, 2, 'K3.1'],
            
            // K3.2
            ['K3.2.1', 'Kebijakan dan Prosedur Persyaratan Kelulusan', 'Paparkan kebijakan dan prosedur yang diterapkan Prodi untuk secara efektif memastikan pemenuhan semua persyaratan kelulusan oleh para lulusannya.', 2, 1, 1, 'K3.2'],
            ['K3.2.2', 'Bukti Pencapaian Seluruh CPL oleh Lulusan', 'Paparkan bagaimana Prodi memastikan bahwa semua CPL telah dicapai oleh semua lulusannya. Proses dan hasil kaji ulang persyaratan kelulusan terdokumentasi secara resmi dan disimpan sebagai rekaman tetap.', 2, 1, 2, 'K3.2'],
            
            // K4.1
            ['K4.1.1', 'Analisis dan Evaluasi Periodik Ketercapaian CPL', 'Jelaskan analisis dan evaluasi periodik terhadap hasil pengukuran CPL yang mencakup identifikasi isu, pemenuhan target kinerja, dan akar masalah, disertai dengan bukti-bukti pendukung.', 2, 1, 1, 'K4.1'],
            ['K4.1.2', 'Penggunaan Hasil Evaluasi untuk Peningkatan Mutu', 'Jelaskan bagaimana hasil evaluasi ketercapaian CPL digunakan Program untuk mengambil keputusan-keputusan peningkatan mutu dan kinerja secara berkelanjutan, misalnya terkait capaian pembelajaran, kurikulum, metode pembelajaran, asesmen, dan sumber daya.', 2, 1, 2, 'K4.1'],
            
            // K4.2
            ['K4.2.1', 'Bukti Siklus PDCA Perbaikan Mutu', 'Jelaskan bahwa keputusan-keputusan perbaikan mutu berkelanjutan telah dilaksanakan dan dievaluasi efektivitasnya sebagai bukti siklus PDCA telah berjalan.', 2, 1, 1, 'K4.2'],
            ['K4.2.2', 'Sistem Dokumentasi Tindakan Perbaikan', 'Berikan bukti bahwa implementasi keputusan-keputusan tindakan perbaikan dan hasil evaluasi efektivitasnya terpelihara dalam suatu sistem terdokumentasi dan dapat diakses.', 2, 1, 2, 'K4.2'],
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
