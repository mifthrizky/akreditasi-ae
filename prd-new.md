# Product Requirements Document (PRD)

## Sistem Pedoman Kurikulum Akreditasi IABEE 2026

**Versi:** 2.0.0  
**Tanggal:** April 2026  
**Status:** Final — Siap Development  
**Disusun oleh:** Tim Pengembang

---

## Daftar Isi

1. [Latar Belakang](#1-latar-belakang)
2. [Tujuan Produk](#2-tujuan-produk)
3. [Ruang Lingkup](#3-ruang-lingkup)
4. [Pengguna dan Role](#4-pengguna-dan-role)
5. [Alur Sistem](#5-alur-sistem)
6. [Fitur](#6-fitur)
7. [Tech Stack](#7-tech-stack)
8. [Rancangan Database](#8-rancangan-database)
9. [Struktur Folder Laravel](#9-struktur-folder-laravel)
10. [API Endpoint](#10-api-endpoint)
11. [Aturan Bisnis](#11-aturan-bisnis)
12. [Desain Awal — Wireframe & Alur I/O](#12-desain-awal--wireframe--alur-io)
13. [Rumus Perhitungan Skor](#13-rumus-perhitungan-skor)
14. [Verifikasi & Test Cases](#14-verifikasi--test-cases)
15. [Validasi Input & Edge Cases](#15-validasi-input--edge-cases)
16. [Kriteria Penerimaan](#16-kriteria-penerimaan)
17. [Pertanyaan Klarifikasi untuk Stakeholder](#17-pertanyaan-klarifikasi-untuk-stakeholder)

---

## 1. Latar Belakang

Proses persiapan akreditasi internasional IABEE (_Institution of Accreditation Board for Engineering Education_) memerlukan kelengkapan dokumen kurikulum yang terstruktur dan terverifikasi. Saat ini proses pengecekan kesiapan dokumen masih dilakukan secara manual sehingga sulit dipantau kemajuannya secara real-time.

Sistem ini dibangun untuk membantu satu jurusan dengan beberapa program studi dalam mempersiapkan kelengkapan dokumen kurikulum sesuai karakteristik IABEE 2026, mulai dari pengisian template, validasi dokumen, hingga generate laporan kesiapan akreditasi.

### 1.1 Masalah yang Diselesaikan

| Masalah Saat Ini                        | Solusi dalam Sistem                             |
| --------------------------------------- | ----------------------------------------------- |
| Pengecekan manual, rawan human error    | Kalkulasi skor otomatis berbasis bobot          |
| Tidak ada visibilitas progres real-time | Dashboard dengan progress bar per karakteristik |
| Dokumen tersebar di berbagai folder     | Satu platform terpusat per program studi        |
| Tidak ada feedback terstruktur ke dosen | Sistem komentar validator per submission        |
| Sulit generate laporan kesiapan formal  | Generate laporan PDF + grafik radar otomatis    |

---

## 2. Tujuan Produk

- Menyediakan platform terpusat untuk pengelolaan dokumen kurikulum per program studi
- Memastikan setiap karakteristik IABEE 2026 terdokumentasi dan terverifikasi dengan benar
- Menghasilkan laporan kesiapan akreditasi otomatis dalam format PDF beserta grafik visualisasi
- Mengurangi risiko dokumen yang diisi sembarangan melalui proses validasi oleh validator

---

## 3. Ruang Lingkup

### 3.1 Masuk dalam Scope

- Manajemen karakteristik dan sub-karakteristik kurikulum IABEE 2026
- Template per karakteristik (checklist, upload dokumen, input numerik, narasi)
- Alur submission dokumen oleh dosen dan validasi oleh validator
- Laporan kesiapan kurikulum per program studi (PDF + grafik)
- Manajemen user dengan tiga role: admin, dosen, validator
- Kalkulasi skor real-time berbasis bobot item template
- Gap analysis otomatis terhadap standar minimum IABEE

### 3.2 Tidak Masuk dalam Scope (Saat Ini)

- RPS / silabus per mata kuliah
- Karakteristik akreditasi non-kurikulum (fasilitas, penelitian, dll.)
- Integrasi dengan sistem kampus (SIAKAD, e-learning)
- Penilaian langsung dari asesor IABEE eksternal
- OCR / ekstraksi semantik isi dokumen PDF
- Versi mobile native (Android/iOS)

---

## 4. Pengguna dan Role

| Role                  | Deskripsi                                  | Akses Utama                                         |
| --------------------- | ------------------------------------------ | --------------------------------------------------- |
| **Admin**             | Mengelola seluruh data master sistem       | CRUD prodi, user, karakteristik, template           |
| **Dosen / Tim Prodi** | Mengisi dan mengsubmit dokumen kurikulum   | Dashboard prodi, isi template, submit, lihat status |
| **Validator**         | Memeriksa kesesuaian dokumen yang diupload | Antrian review, approve / kembalikan + catatan      |

---

## 5. Alur Sistem

### 5.1 Alur Umum

```
Admin setup → Dosen isi template → Submit → Validator review → Approved → Generate laporan
```

### 5.2 Status Submission

```
draft → submitted → [diterima | revisi | ditolak]
                         ↑
                    revisi kembali ke draft → submitted lagi
```

| Status      | Keterangan                                   |
| ----------- | -------------------------------------------- |
| `draft`     | Dosen masih mengisi, belum disubmit          |
| `submitted` | Sudah disubmit, menunggu review validator    |
| `diterima`  | Validator menyetujui dokumen                 |
| `revisi`    | Dikembalikan ke dosen dengan catatan         |
| `ditolak`   | Dokumen tidak relevan, perlu pengisian ulang |

**Aturan transisi:**

- `draft` → `submitted`: Hanya dosen, skor sementara minimal memenuhi threshold
- `submitted` → `diterima`: Hanya validator
- `submitted` → `revisi`: Hanya validator, **komentar wajib diisi**
- `submitted` → `ditolak`: Hanya validator, **komentar wajib diisi**
- `revisi` → `draft`: Otomatis setelah validator mengirim revisi
- Jika status sudah `diterima`, dosen **tidak bisa** edit tanpa konfirmasi admin

### 5.3 Dua Lapis Laporan

| Laporan         | Trigger         | Konten                                                     |
| --------------- | --------------- | ---------------------------------------------------------- |
| Laporan progres | Real-time       | Jumlah submission per status, progres pengisian            |
| Laporan resmi   | Generate manual | Hanya submission berstatus `diterima`, skor + gap analysis |

---

## 6. Fitur

### 6.1 Admin

- CRUD program studi dan assign user ke prodi
- CRUD karakteristik IABEE (termasuk sub-karakteristik via `parent_id`)
- Konfigurasi template per karakteristik: tipe item, bobot, wajib/opsional
- Manajemen user dan role
- Melihat progress semua prodi dalam satu tampilan ringkasan
- Reset submission jika diperlukan (dengan audit log)

### 6.2 Dosen

- Dashboard program studi: daftar karakteristik + status + skor sementara tiap karakteristik
- Halaman detail karakteristik: panduan pengisian + template
- Isi template: checklist, upload dokumen (PDF/DOCX/XLSX), input numerik, narasi
- Preview skor real-time saat mengisi (sebelum disubmit)
- Simpan sebagai draft atau submit untuk validasi
- Melihat catatan dari validator dan melakukan revisi
- Riwayat lengkap perubahan status submission

### 6.3 Validator

- Dashboard antrian submission yang menunggu review
- Preview dokumen yang diupload langsung di browser
- Memberi status: disetujui / revisi / ditolak + komentar wajib saat revisi/tolak
- Riwayat validasi yang pernah dilakukan
- Dapat menambah catatan internal per submission

### 6.4 Laporan

- Skor per karakteristik dihitung otomatis dari bobot item yang terpenuhi
- Grafik radar / spider chart skor vs standar minimum IABEE
- Gap analysis: karakteristik yang belum terpenuhi + rekomendasi
- Export laporan ke PDF
- Histori laporan tersimpan per prodi

---

## 7. Tech Stack

| Komponen          | Teknologi                                   |
| ----------------- | ------------------------------------------- |
| Backend Framework | Laravel 11                                  |
| Database          | PostgreSQL 16                               |
| Frontend          | Blade + Alpine.js (atau Inertia.js + Vue 3) |
| File Storage      | Laravel Storage (local / S3)                |
| PDF Generator     | Laravel DomPDF atau Browsershot             |
| Grafik            | Chart.js (frontend)                         |
| Authentication    | Laravel Sanctum / Breeze                    |
| Queue             | Laravel Queue (untuk generate PDF)          |

---

## 8. Rancangan Database

> Menggunakan PostgreSQL. Tipe `SERIAL` menggantikan `INT AUTO_INCREMENT`, dan `TEXT` digunakan untuk string panjang.

### 8.1 Skema Lengkap

```sql
-- ============================================================
-- DATABASE SISTEM PEDOMAN KURIKULUM AKREDITASI IABEE 2026
-- Tech stack: Laravel 11 + PostgreSQL 16
-- ============================================================

DROP TABLE IF EXISTS laporan         CASCADE;
DROP TABLE IF EXISTS validasi        CASCADE;
DROP TABLE IF EXISTS dokumen         CASCADE;
DROP TABLE IF EXISTS submission_item CASCADE;
DROP TABLE IF EXISTS submission      CASCADE;
DROP TABLE IF EXISTS template_item   CASCADE;
DROP TABLE IF EXISTS kriteria        CASCADE;
DROP TABLE IF EXISTS user_prodi      CASCADE;
DROP TABLE IF EXISTS program_studi   CASCADE;
DROP TABLE IF EXISTS users           CASCADE;

-- ------------------------------------------------------------
-- TABEL: users
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    user_id    SERIAL       PRIMARY KEY,
    email      VARCHAR(255) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    nama       VARCHAR(255) NOT NULL,
    role       VARCHAR(20)  NOT NULL CHECK (role IN ('admin', 'dosen', 'validator')),
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- TABEL: program_studi
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS program_studi (
    prodi_id   SERIAL       PRIMARY KEY,
    kode       VARCHAR(20)  NOT NULL UNIQUE,
    nama       VARCHAR(255) NOT NULL,
    jurusan    VARCHAR(10)  NOT NULL CHECK (jurusan IN ('ME', 'FE', 'DE', 'AE')),
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- TABEL: user_prodi (pivot many-to-many user <-> prodi)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS user_prodi (
    userprodi_id SERIAL PRIMARY KEY,
    user_id      INT    NOT NULL,
    prodi_id     INT    NOT NULL,
    FOREIGN KEY (user_id)  REFERENCES users(user_id)          ON DELETE CASCADE,
    FOREIGN KEY (prodi_id) REFERENCES program_studi(prodi_id) ON DELETE CASCADE,
    UNIQUE (user_id, prodi_id)
);

-- ------------------------------------------------------------
-- TABEL: kriteria
-- level 0 = kriteria utama (pengelompok, tidak punya template)
-- level 1 = sub-kriteria (punya template & submission)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS kriteria (
    kriteria_id SERIAL       PRIMARY KEY,
    parent_id   INT          DEFAULT NULL,
    kode        VARCHAR(10)  NOT NULL UNIQUE,
    nama        VARCHAR(255) NOT NULL,
    deskripsi   TEXT,
    level       INT          NOT NULL DEFAULT 0,
    bobot       FLOAT        NOT NULL,
    urutan      INT          NOT NULL,
    CONSTRAINT fk_kriteria_parent
        FOREIGN KEY (parent_id) REFERENCES kriteria(kriteria_id)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- ------------------------------------------------------------
-- TABEL: template_item
-- tipe: checklist | upload | numerik | narasi
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS template_item (
    template_id       SERIAL       PRIMARY KEY,
    kriteria_id       INT          NOT NULL,
    tipe              VARCHAR(20)  NOT NULL CHECK (tipe IN ('checklist', 'upload', 'numerik', 'narasi')),
    label             VARCHAR(255) NOT NULL,
    hint              VARCHAR(255),
    wajib             BOOLEAN      NOT NULL DEFAULT TRUE,
    bobot             FLOAT        NOT NULL DEFAULT 0,
    nilai_min_numerik FLOAT,
    urutan            INT          NOT NULL,
    FOREIGN KEY (kriteria_id) REFERENCES kriteria(kriteria_id) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- TABEL: submission
-- Satu submission = satu prodi mengerjakan satu sub-kriteria
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS submission (
    submission_id SERIAL      PRIMARY KEY,
    prodi_id      INT         NOT NULL,
    kriteria_id   INT         NOT NULL,
    user_id       INT         NOT NULL,
    status        VARCHAR(20) NOT NULL DEFAULT 'draft'
                              CHECK (status IN ('draft', 'submitted', 'diterima', 'revisi', 'ditolak')),
    skor          FLOAT,
    submitted_at  TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (prodi_id, kriteria_id),
    FOREIGN KEY (prodi_id)    REFERENCES program_studi(prodi_id) ON DELETE CASCADE,
    FOREIGN KEY (kriteria_id) REFERENCES kriteria(kriteria_id)   ON DELETE CASCADE,
    FOREIGN KEY (user_id)     REFERENCES users(user_id)          ON DELETE CASCADE
);

CREATE OR REPLACE FUNCTION update_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_submission_updated_at
    BEFORE UPDATE ON submission
    FOR EACH ROW EXECUTE FUNCTION update_updated_at();

-- ------------------------------------------------------------
-- TABEL: submission_item
-- Jawaban dosen per item template dalam satu submission
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS submission_item (
    subitem_id       SERIAL  PRIMARY KEY,
    submission_id    INT     NOT NULL,
    template_item_id INT     NOT NULL,
    nilai_checklist  BOOLEAN,
    nilai_teks       TEXT,
    nilai_numerik    FLOAT,
    FOREIGN KEY (submission_id)    REFERENCES submission(submission_id)    ON DELETE CASCADE,
    FOREIGN KEY (template_item_id) REFERENCES template_item(template_id)  ON DELETE CASCADE,
    UNIQUE (submission_id, template_item_id)
);

-- ------------------------------------------------------------
-- TABEL: dokumen
-- File yang diupload oleh dosen per submission_item (tipe upload)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS dokumen (
    dokumen_id  SERIAL       PRIMARY KEY,
    subitem_id  INT          NOT NULL,
    nama_file   VARCHAR(255) NOT NULL,
    path_file   VARCHAR(500) NOT NULL,
    tipe_file   VARCHAR(50)  NOT NULL,
    ukuran_file INT          NOT NULL,
    uploaded_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (subitem_id) REFERENCES submission_item(subitem_id) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- TABEL: validasi
-- Hasil review validator untuk satu submission
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS validasi (
    validasi_id   SERIAL      PRIMARY KEY,
    submission_id INT         NOT NULL UNIQUE,
    validator_id  INT         NOT NULL,
    status        VARCHAR(20) NOT NULL CHECK (status IN ('disetujui', 'revisi', 'ditolak')),
    komentar      TEXT,
    validated_at  TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (submission_id) REFERENCES submission(submission_id) ON DELETE CASCADE,
    FOREIGN KEY (validator_id)  REFERENCES users(user_id)            ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- TABEL: laporan
-- Histori laporan kesiapan kurikulum yang pernah di-generate
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS laporan (
    laporan_id   SERIAL       PRIMARY KEY,
    prodi_id     INT          NOT NULL,
    generated_by INT          NOT NULL,
    skor_total   FLOAT,
    path_pdf     VARCHAR(500) NOT NULL,
    generated_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (prodi_id)     REFERENCES program_studi(prodi_id) ON DELETE CASCADE,
    FOREIGN KEY (generated_by) REFERENCES users(user_id)          ON DELETE CASCADE
);
```

### 8.2 Ringkasan Tabel dan Relasi

| Tabel             | Deskripsi                                      | Relasi Utama                                                           |
| ----------------- | ---------------------------------------------- | ---------------------------------------------------------------------- |
| `users`           | Data pengguna dan role                         | → `user_prodi`, `submission`, `validasi`, `laporan`                    |
| `program_studi`   | Daftar program studi                           | → `user_prodi`, `submission`, `laporan`                                |
| `user_prodi`      | Pivot user ↔ prodi                             | ← `users`, `program_studi`                                             |
| `kriteria`        | Karakteristik IABEE (hierarki via `parent_id`) | Self-referencing → `template_item`, `submission`                       |
| `template_item`   | Item form per sub-kriteria                     | ← `kriteria` → `submission_item`                                       |
| `submission`      | Pengerjaan satu sub-kriteria oleh satu prodi   | ← `program_studi`, `kriteria`, `users` → `submission_item`, `validasi` |
| `submission_item` | Jawaban per item template                      | ← `submission`, `template_item` → `dokumen`                            |
| `dokumen`         | File yang diupload                             | ← `submission_item`                                                    |
| `validasi`        | Hasil review validator                         | ← `submission`, `users`                                                |
| `laporan`         | Histori laporan PDF                            | ← `program_studi`, `users`                                             |

### 8.3 Catatan Penting MySQL → PostgreSQL

| MySQL                         | PostgreSQL                         | Keterangan                     |
| ----------------------------- | ---------------------------------- | ------------------------------ |
| `INT AUTO_INCREMENT`          | `SERIAL`                           | Auto-increment di PostgreSQL   |
| `ENUM('a','b')`               | `VARCHAR CHECK (col IN ('a','b'))` | CHECK lebih fleksibel          |
| `ON UPDATE CURRENT_TIMESTAMP` | Trigger `update_updated_at()`      | Harus menggunakan trigger      |
| Nama tabel `user`             | `users`                            | `user` adalah reserved keyword |

---

## 9. Struktur Folder Laravel

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── KriteriaController.php
│   │   │   ├── TemplateItemController.php
│   │   │   ├── ProgramStudiController.php
│   │   │   └── UserController.php
│   │   ├── Dosen/
│   │   │   ├── SubmissionController.php
│   │   │   ├── SubmissionItemController.php
│   │   │   └── DokumenController.php
│   │   ├── Validator/
│   │   │   └── ValidasiController.php
│   │   └── LaporanController.php
│   └── Middleware/
│       └── RoleMiddleware.php
├── Models/
│   ├── User.php
│   ├── ProgramStudi.php
│   ├── UserProdi.php
│   ├── Kriteria.php
│   ├── TemplateItem.php
│   ├── Submission.php
│   ├── SubmissionItem.php
│   ├── Dokumen.php
│   ├── Validasi.php
│   └── Laporan.php
└── Services/
    ├── SkorService.php        # Hitung skor submission otomatis
    └── LaporanService.php     # Generate PDF laporan
```

---

## 10. API Endpoint

| Method | Endpoint                              | Role        | Deskripsi                            |
| ------ | ------------------------------------- | ----------- | ------------------------------------ |
| GET    | `/admin/kriteria`                     | admin       | Daftar semua kriteria                |
| POST   | `/admin/kriteria`                     | admin       | Tambah kriteria baru                 |
| POST   | `/admin/kriteria/{id}/template`       | admin       | Tambah item template                 |
| GET    | `/dosen/prodi/{id}/kriteria`          | dosen       | Daftar kriteria + status per prodi   |
| GET    | `/dosen/submission/{id}`              | dosen       | Detail submission dan item-nya       |
| POST   | `/dosen/submission`                   | dosen       | Buat atau update submission (draft)  |
| PATCH  | `/dosen/submission-item/{id}`         | dosen       | Update satu item (real-time scoring) |
| POST   | `/dosen/submission/{id}/submit`       | dosen       | Submit untuk validasi                |
| POST   | `/dosen/submission-item/{id}/dokumen` | dosen       | Upload dokumen                       |
| DELETE | `/dosen/dokumen/{id}`                 | dosen       | Hapus file dokumen                   |
| GET    | `/validator/antrian`                  | validator   | Daftar submission menunggu review    |
| POST   | `/validator/validasi/{submission_id}` | validator   | Setujui / kembalikan submission      |
| GET    | `/laporan/{prodi_id}`                 | semua       | Lihat laporan progres prodi          |
| POST   | `/laporan/{prodi_id}/generate`        | dosen/admin | Generate laporan PDF resmi           |

---

## 11. Aturan Bisnis

1. **Template hanya untuk sub-kriteria** — `template_item` hanya boleh berelasi ke `kriteria` dengan `level = 1`. Kriteria utama (`level = 0`) hanya sebagai pengelompok.
2. **Satu submission per sub-kriteria per prodi** — Dijaga oleh `UNIQUE (prodi_id, kriteria_id)` di tabel `submission`.
3. **Skor dihitung otomatis** — Saat dosen mengisi `submission_item`, sistem menghitung skor dari bobot item yang terpenuhi. Skor disimpan di kolom `skor` tabel `submission`.
4. **Laporan resmi hanya dari submission `diterima`** — Saat generate laporan, hanya submission dengan status `diterima` yang dihitung ke skor total.
5. **Validator wajib isi komentar saat revisi/tolak** — Validasi pada status `revisi` atau `ditolak` wajib mengisi kolom `komentar`.
6. **Kedalaman hierarki maksimal 2 level** — Level 0 (kriteria utama) dan level 1 (sub-kriteria). Tidak ada sub-sub-kriteria.
7. **Bobot item wajib total 100%** — Seluruh `bobot` dari `template_item` per sub-kriteria harus menjumlah tepat 100. Sistem menolak konfigurasi template jika total bobot tidak 100.
8. **Upload dokumen otomatis dihitung terpenuhi** — Ketika file berhasil diunggah, item tipe `upload` langsung dianggap `terpenuhi` untuk keperluan kalkulasi skor sementara. Namun validator tetap dapat menolak atau meminta perbaikan file.
9. **Submission yang sudah `diterima` terkunci** — Dosen tidak dapat mengubah isi submission berstatus `diterima` tanpa persetujuan admin. Admin dapat membuka kunci dengan mengubah status kembali ke `draft` beserta audit log.
10. **Nilai numerik wajib memenuhi nilai minimum** — Item bertipe `numerik` dengan `nilai_min_numerik` tidak null hanya dianggap terpenuhi jika nilai yang diinput ≥ `nilai_min_numerik`.

---

## 12. Desain Awal — Wireframe & Alur I/O

### 12.1 Peta Halaman Aplikasi

```
[ LOGIN ]
    │
    ├──→ [ ADMIN DASHBOARD ]
    │         ├── Kelola Prodi
    │         ├── Kelola Karakteristik & Template
    │         ├── Kelola User
    │         └── Ringkasan Progress Semua Prodi
    │
    ├──→ [ DOSEN DASHBOARD ]
    │         ├── Daftar Karakteristik + Status + Skor
    │         ├── Form Pengisian Karakteristik ──→ Preview Skor Real-time
    │         ├── Upload Dokumen
    │         └── Riwayat Review & Revisi
    │
    ├──→ [ VALIDATOR DASHBOARD ]
    │         ├── Antrian Submission
    │         ├── Form Review (Preview Dokumen + Keputusan)
    │         └── Riwayat Validasi
    │
    └──→ [ LAPORAN ]
              ├── Progress Real-time
              ├── Laporan Resmi (hanya `diterima`)
              ├── Grafik Radar vs Standar IABEE
              ├── Gap Analysis
              └── Export PDF
```

### 12.2 Wireframe — Dosen Dashboard

```
┌─────────────────────────────────────────────────────────┐
│  IABEE Checker         [Notif 2]   Halo, Dr. Budi ▾    │
├─────────────────────────────────────────────────────────┤
│  Program Studi: Teknik Informatika     Tahun: 2024      │
│  Progress Keseluruhan: 45%  [████████░░░░░░░░░░]        │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  DAFTAR KARAKTERISTIK                   Filter: [Semua▾]│
│  ┌─────────────────────────────────────────────────┐   │
│  │ 1. Program Outcomes (PO)               [75/100] │   │
│  │    Status: ✅ DITERIMA  │  Update: 2 Apr 2026   │   │
│  │    [Lihat Detail]  [Download Dokumen]           │   │
│  ├─────────────────────────────────────────────────┤   │
│  │ 2. Curriculum Content & Design         [50/100] │   │
│  │    Status: ⏳ MENUNGGU REVIEW  │  1 Apr 2026   │   │
│  │    [Lihat Detail]  [Download Dokumen]           │   │
│  ├─────────────────────────────────────────────────┤   │
│  │ 3. Teaching & Learning Methods         [30/100] │   │
│  │    Status: ✏️ DRAFT  │  Update: 29 Mar 2026    │   │
│  │    [Lanjut Isi]  [Download Dokumen]             │   │
│  ├─────────────────────────────────────────────────┤   │
│  │ 4. Assessment Strategy                  [0/100] │   │
│  │    Status: ➖ BELUM DIMULAI                      │   │
│  │    [Mulai Isi]                                  │   │
│  └─────────────────────────────────────────────────┘   │
│                                                         │
│  [Generate Laporan Progres]  [Lihat Riwayat Review]     │
└─────────────────────────────────────────────────────────┘
```

### 12.3 Wireframe — Form Pengisian Karakteristik

```
┌─────────────────────────────────────────────────────────────────┐
│  [← Kembali]   Pengisian: Program Outcomes (PO)   [DRAFT]      │
├──────────────────────────────────────────────┬──────────────────┤
│                                              │  SKOR SEMENTARA  │
│  Sub-kriteria 1.1: PO Alignment              │                  │
│  Pastikan PO selaras dengan visi misi        │  60%             │
│  program studi dan standar IABEE.            │  [████████░░░░]  │
│                                              │                  │
│  CHECKLIST ITEM:                             │  3 dari 5 item   │
│  ☑ PO1: Analisis masalah teknis (20%)        │  terpenuhi       │
│  ☑ PO2: Design solusi sistemik  (20%)        │                  │
│  ☑ PO3: Komunikasi teknis       (20%)        │  Status:         │
│  ☐ PO4: Kerja tim profesional   (20%)        │  PARTIAL         │
│  ☐ PO5: Aspek sosial & etika    (20%)        │  COMPLIANT       │
│                                              │                  │
│  UPLOAD DOKUMEN PENDUKUNG:                   │  ─────────────── │
│  Format: PDF/DOCX/XLSX | Maks: 10MB/file    │  Syarat submit:  │
│  [📎 Pilih File]                             │  Skor ≥ 50%      │
│  • PO_Mapping_2024.pdf   2.1MB  ✅  [Hapus] │                  │
│  • Kurikulum_2024.docx   1.5MB  ✅  [Hapus] │  [SIMPAN DRAFT]  │
│                                              │  [SUBMIT ▶]      │
│  CATATAN DOSEN (Opsional):                   │                  │
│  ┌──────────────────────────────────────┐    │                  │
│  │ PO telah ditetapkan oleh tim ...    │    │                  │
│  │ [min: 0 karakter]                   │    │                  │
│  └──────────────────────────────────────┘    │                  │
│                                              │                  │
│  RIWAYAT REVIEW:                            │                  │
│  2 Apr — Validator A: [REVISI]              │                  │
│  "Item PO4 belum ada bukti dokumen"         │                  │
└──────────────────────────────────────────────┴──────────────────┘
```

### 12.4 Wireframe — Validator Dashboard & Form Review

```
┌─────────────────────────────────────────────────────────┐
│  IABEE Checker              Halo, Dr. Siti (Validator)  │
├─────────────────────────────────────────────────────────┤
│  ANTRIAN REVIEW          Menunggu: 8  │  Selesai: 3     │
│  ┌─────────────────────────────────────────────────┐   │
│  │ ⏳ [1]  Teknik Informatika — Program Outcomes   │   │
│  │     Dosen: Dr. Budi │ Dikirim: 2 Apr, 14:30    │   │
│  │     [📋 Review Sekarang]                         │   │
│  ├─────────────────────────────────────────────────┤   │
│  │ ⏳ [2]  Teknik Sipil — Teaching Methods         │   │
│  │     Dosen: Prof. Rina │ Dikirim: 2 Apr, 10:15  │   │
│  │     [📋 Review Sekarang]                         │   │
│  └─────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘

FORM REVIEW (setelah klik Review Sekarang):
┌─────────────────────────────────────────────────────────┐
│  [← Antrian]  Review: TI — Program Outcomes            │
├──────────────────────────────────┬──────────────────────┤
│  PREVIEW SUBMISSION              │  KEPUTUSAN           │
│                                  │                      │
│  ☑ PO1: Analisis masalah (20%)   │  Status:             │
│  ☑ PO2: Design solusi    (20%)   │  ○ ✅ SETUJUI        │
│  ☑ PO3: Komunikasi       (20%)   │  ○ 🔄 REVISI         │
│  ☐ PO4: Kerja tim        (20%)   │  ○ ❌ TOLAK          │
│  ☐ PO5: Sosial & etika   (20%)   │                      │
│                                  │  Komentar            │
│  Dokumen:                        │  (wajib jika         │
│  • PO_Mapping.pdf  [👁 Preview]  │  revisi/tolak):      │
│  • Kurikulum.docx  [👁 Preview]  │  ┌────────────────┐  │
│                                  │  │ PO4 & PO5     │  │
│  Catatan dosen:                  │  │ perlu bukti... │  │
│  "PO telah ditetapkan..."        │  └────────────────┘  │
│                                  │                      │
│  SKOR SEMENTARA: 60%             │  [SUBMIT KEPUTUSAN]  │
└──────────────────────────────────┴──────────────────────┘
```

### 12.5 Wireframe — Halaman Laporan

```
┌─────────────────────────────────────────────────────────┐
│  LAPORAN KESIAPAN AKREDITASI IABEE 2026                 │
│  Program Studi: Teknik Informatika  │  5 Apr 2026       │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  SKOR TOTAL: 68.3%   Status: ⚠️ HAMPIR COMPLIANT        │
│  Standar Minimum IABEE: 75%  │  Gap: 6.7 poin           │
│  [███████████████░░░░]  68.3%                           │
│                                                         │
│  ┌─────────────────────┐  ┌─────────────────────────┐  │
│  │   GRAFIK RADAR      │  │   SKOR PER KARAKTERISTIK│  │
│  │                     │  │                         │  │
│  │     PO (75%)        │  │ 1. PO            75%  ✓ │  │
│  │    /        \       │  │ 2. Curriculum    85%  ✓ │  │
│  │  Alumni  Curr.      │  │ 3. Teaching      80%  ✓ │  │
│  │ (68%)   (85%)       │  │ 4. Assessment    70%  ⚠ │  │
│  │    \        /       │  │ 5. Research      64%  ❌ │  │
│  │  Research(64%)      │  │ 6. Alumni        68%  ⚠ │  │
│  │  --- Prodi TI       │  └─────────────────────────┘  │
│  │  ─── Min. Std (75%) │                               │
│  └─────────────────────┘                               │
│                                                         │
│  GAP ANALYSIS                                           │
│  ❌ KRITIS:  Research (64%) — gap 11 poin              │
│  ⚠ PERLU:   Assessment (70%), Alumni (68%)             │
│  ✅ KUAT:   Curriculum (85%), Teaching (80%)           │
│                                                         │
│  [⬇ Download PDF]  [🖨 Print]  [Generate Ulang]       │
└─────────────────────────────────────────────────────────┘
```

### 12.6 Diagram Alur Input-Output — Pengisian Submission

```
[DOSEN LOGIN]
     │
     ▼
[PILIH KARAKTERISTIK]
     │ INPUT: klik karakteristik dari dashboard
     │ PROSES: load template items dari DB
     │ OUTPUT: form pengisian tampil
     ▼
[ISI CHECKLIST ITEM]
     │ INPUT: centang/uncentang checkbox
     │ PROSES:
     │   1. UPDATE submission_item SET nilai_checklist = true/false
     │   2. SkorService::recalculate(submission_id)
     │      → SUM(bobot) WHERE nilai_checklist = true
     │   3. UPDATE submission SET skor = hasil
     │ OUTPUT: progress bar & skor real-time update di UI
     ▼
[UPLOAD DOKUMEN]
     │ INPUT: pilih file (PDF/DOCX/XLSX, maks 10MB)
     │ PROSES:
     │   1. Validasi tipe & ukuran
     │   2. Store ke Laravel Storage
     │   3. INSERT ke tabel dokumen
     │   4. UPDATE submission_item SET nilai_checklist = true (tipe upload)
     │   5. Recalculate skor
     │ OUTPUT: preview file + skor update
     │ ERROR: jika format/ukuran salah → pesan error, tidak tersimpan
     ▼
[SIMPAN DRAFT atau SUBMIT]
     │ DRAFT:
     │   PROSES: UPDATE submission SET status = 'draft'
     │   OUTPUT: form tetap terbuka, notif "Tersimpan"
     │
     │ SUBMIT:
     │   PROSES:
     │   1. Cek skor ≥ threshold minimum
     │   2. UPDATE submission SET status = 'submitted', submitted_at = NOW()
     │   3. Kirim notifikasi ke validator (email/in-app)
     │   OUTPUT: form jadi read-only, redirect ke dashboard
     │   ERROR: jika skor < threshold → tombol submit disabled
     ▼
[VALIDATOR REVIEW]
     │ INPUT: klik "Review" dari antrian
     │ PROSES: load submission + items + dokumen
     │ OUTPUT: halaman review tampil
     ▼
[KEPUTUSAN VALIDATOR]
     │ SETUJUI:
     │   PROSES: UPDATE submission SET status = 'diterima'
     │            INSERT INTO validasi (status='disetujui')
     │   OUTPUT: submission terkunci, notif ke dosen
     │
     │ REVISI:
     │   PROSES: UPDATE submission SET status = 'revisi'
     │            INSERT INTO validasi (status='revisi', komentar=...)
     │   OUTPUT: notif ke dosen, submission bisa diedit kembali
     │   VALIDASI: komentar wajib diisi
     │
     │ TOLAK:
     │   PROSES: UPDATE submission SET status = 'ditolak'
     │            INSERT INTO validasi (status='ditolak', komentar=...)
     │   OUTPUT: notif ke dosen, dosen perlu isi ulang
     │   VALIDASI: komentar wajib diisi
     ▼
[GENERATE LAPORAN]
     │ INPUT: klik "Generate Laporan Resmi"
     │ PROSES:
     │   1. Query: SELECT * FROM submission WHERE status = 'diterima'
     │   2. LaporanService::hitungGrandTotal(prodi_id)
     │   3. Render grafik radar via Chart.js
     │   4. Generate PDF via DomPDF (async via Laravel Queue)
     │   5. INSERT INTO laporan (path_pdf, skor_total, ...)
     │ OUTPUT: file PDF tersedia untuk download
     └──────────────────────────
```

---

## 13. Rumus Perhitungan Skor

### 13.1 Definisi Skor

Skor adalah representasi persentase dari total bobot item template yang telah **terpenuhi** dalam sebuah submission, dibandingkan terhadap total bobot keseluruhan item dalam sub-karakteristik tersebut.

### 13.2 Formula Dasar — Skor per Submission

```
Skor_submission (%) =
    SUM(bobot_item × status_terpenuhi_item)
    ─────────────────────────────────────── × 100
           SUM(bobot_item_total)

Dimana:
  status_terpenuhi_item = 1 jika item terpenuhi, 0 jika tidak
  SUM(bobot_item_total) = 100 (bobot selalu total 100 per sub-kriteria)

Sehingga formula menyederhanakan menjadi:

  Skor_submission (%) = SUM(bobot_item × status_terpenuhi_item)
```

**Contoh Perhitungan:**

| Item                   | Bobot    | Status       | Kontribusi |
| ---------------------- | -------- | ------------ | ---------- |
| PO1: Analisis masalah  | 20%      | ✅ Terpenuhi | 20         |
| PO2: Design solusi     | 20%      | ✅ Terpenuhi | 20         |
| PO3: Komunikasi teknis | 20%      | ✅ Terpenuhi | 20         |
| PO4: Kerja tim         | 20%      | ✗ Tidak      | 0          |
| PO5: Sosial & etika    | 20%      | ✗ Tidak      | 0          |
| **Total**              | **100%** |              | **60%**    |

**Skor submission = 60%**

**Query SQL yang digunakan `SkorService`:**

```sql
SELECT
    COALESCE(SUM(
        ti.bobot *
        CASE
            WHEN ti.tipe = 'checklist' THEN (si.nilai_checklist::int)
            WHEN ti.tipe = 'upload'    THEN (
                CASE WHEN EXISTS (
                    SELECT 1 FROM dokumen d WHERE d.subitem_id = si.subitem_id
                ) THEN 1 ELSE 0 END
            )
            WHEN ti.tipe = 'numerik'   THEN (
                CASE WHEN si.nilai_numerik IS NOT NULL
                          AND (ti.nilai_min_numerik IS NULL
                               OR si.nilai_numerik >= ti.nilai_min_numerik)
                     THEN 1 ELSE 0 END
            )
            WHEN ti.tipe = 'narasi'    THEN 0  -- narasi tidak dihitung skor
            ELSE 0
        END
    ), 0) AS skor
FROM submission_item si
JOIN template_item ti ON ti.template_id = si.template_item_id
WHERE si.submission_id = :submission_id;
```

### 13.3 Formula Grand Total — Skor Keseluruhan Prodi

```
Skor_Total_Prodi (%) =
    SUM(Skor_submission_i × Bobot_kriteria_i)
    ─────────────────────────────────────────
                  SUM(Bobot_kriteria_i)

Dimana:
  Skor_submission_i = Skor submission ke-i (hanya yang berstatus 'diterima')
  Bobot_kriteria_i  = Bobot karakteristik ke-i (field bobot di tabel kriteria)
  SUM(Bobot_kriteria) = 100
```

**Contoh Grand Total:**

| Karakteristik       | Skor | Bobot    | Nilai Tertimbang |
| ------------------- | ---- | -------- | ---------------- |
| Program Outcomes    | 75%  | 15%      | 11.25            |
| Curriculum Content  | 85%  | 20%      | 17.00            |
| Teaching Methods    | 80%  | 15%      | 12.00            |
| Assessment Strategy | 70%  | 15%      | 10.50            |
| Facilities          | 72%  | 10%      | 7.20             |
| Research            | 64%  | 10%      | 6.40             |
| Alumni Engagement   | 68%  | 15%      | 10.20            |
| **Total**           |      | **100%** | **74.55%**       |

**Grand Total Skor Prodi = 74.55%**

**Query SQL Grand Total:**

```sql
SELECT
    ROUND(
        SUM(s.skor * k.bobot) / NULLIF(SUM(k.bobot), 0),
        2
    ) AS skor_total
FROM submission s
JOIN kriteria k ON k.kriteria_id = s.kriteria_id
WHERE s.prodi_id    = :prodi_id
  AND s.status      = 'diterima'
  AND k.level       = 1;
```

### 13.4 Threshold dan Status Kesiapan

| Rentang Skor | Status          | Label UI            |
| ------------ | --------------- | ------------------- |
| ≥ 80%        | Sangat Siap     | ✅ FULLY COMPLIANT  |
| 75% – 79.99% | Siap            | ✅ COMPLIANT        |
| 60% – 74.99% | Hampir Siap     | ⚠️ ALMOST COMPLIANT |
| < 60%        | Perlu Perbaikan | ❌ NOT COMPLIANT    |

- **Threshold minimum IABEE = 75%** untuk status overall COMPLIANT
- Submission individu dengan skor < 50% tidak dapat disubmit (tombol Submit disabled)
- Karakteristik dengan skor < 70% akan ditandai sebagai "area kritis" dalam laporan

### 13.5 Status Terpenuhi per Tipe Item

| Tipe Item   | Kondisi Terpenuhi                                                    | Kontribusi ke Skor |
| ----------- | -------------------------------------------------------------------- | ------------------ |
| `checklist` | `nilai_checklist = TRUE`                                             | ✅ Ya              |
| `upload`    | Minimal 1 file dokumen terupload                                     | ✅ Ya              |
| `numerik`   | `nilai_numerik IS NOT NULL` AND `nilai_numerik >= nilai_min_numerik` | ✅ Ya              |
| `narasi`    | Diisi atau tidak — **tidak dihitung skor**                           | ❌ Tidak           |

> **Catatan:** Item `narasi` bersifat dokumentatif, tidak berkontribusi ke skor. Bobot item `narasi` harus di-set `0` oleh admin saat konfigurasi template.

---

## 14. Verifikasi & Test Cases

### 14.1 Test Case 1 — Submission 100% Lengkap

**Skenario:** 5 item checklist, bobot merata 20% masing-masing, semua dicentang.

| Item   | Bobot | Status | Kontribusi |
| ------ | ----- | ------ | ---------- |
| Item 1 | 20%   | ✅     | 20         |
| Item 2 | 20%   | ✅     | 20         |
| Item 3 | 20%   | ✅     | 20         |
| Item 4 | 20%   | ✅     | 20         |
| Item 5 | 20%   | ✅     | 20         |

**Expected Output:**

```
Skor           : 100%
Status         : FULLY COMPLIANT
Tombol Submit  : ENABLED
```

**Verifikasi Manual:** 20+20+20+20+20 = 100 ✓

**Assertion:**

```php
$this->assertEquals(100.0, $skorService->hitung($submission_id));
$this->assertEquals('FULLY_COMPLIANT', $laporan->statusLabel());
```

---

### 14.2 Test Case 2 — Submission Partial (60%)

**Skenario:** 5 item checklist bobot merata, hanya 3 yang dicentang.

| Item   | Bobot | Status | Kontribusi |
| ------ | ----- | ------ | ---------- |
| Item 1 | 20%   | ✅     | 20         |
| Item 2 | 20%   | ✅     | 20         |
| Item 3 | 20%   | ✅     | 20         |
| Item 4 | 20%   | ✗      | 0          |
| Item 5 | 20%   | ✗      | 0          |

**Expected Output:**

```
Skor           : 60%
Status         : ALMOST COMPLIANT
Tombol Submit  : ENABLED (≥ 50%)
```

**Verifikasi Manual:** 20+20+20+0+0 = 60 ✓

---

### 14.3 Test Case 3 — Upload Dokumen

**Skenario:** Item bertipe `upload`, dosen mengunggah 1 file valid.

| Langkah        | Input                                       | Expected                  |
| -------------- | ------------------------------------------- | ------------------------- |
| 1. Pilih file  | `RPS_2024.pdf` (2.3MB)                      | Validasi format & size: ✓ |
| 2. Upload      | File binary                                 | File tersimpan di storage |
| 3. Record DB   | `INSERT INTO dokumen`                       | Row baru terbuat          |
| 4. Update skor | `nilai_checklist = true` di submission_item | Skor naik sesuai bobot    |
| 5. Preview     | Klik nama file                              | File terbuka di browser   |

**Assertion:**

```php
$this->assertFileExists(storage_path($dokumen->path_file));
$this->assertEquals('terpenuhi', $submissionItem->statusLabel());
```

---

### 14.4 Test Case 4 — Input Numerik

**Skenario:** Item bertipe `numerik` dengan `nilai_min_numerik = 50`.

| Sub-test | Input              | Expected                               |
| -------- | ------------------ | -------------------------------------- |
| 4A       | `82.5`             | ✅ Terpenuhi, kontribusi = bobot penuh |
| 4B       | `45.0`             | ✗ Tidak terpenuhi (di bawah minimum)   |
| 4C       | `"abc"`            | ❌ Validasi error: bukan angka         |
| 4D       | `50.0` (tepat min) | ✅ Terpenuhi (boundary inclusive)      |

**Pesan error untuk 4B:** "Nilai harus minimal 50."  
**Pesan error untuk 4C:** "Input harus berupa angka."

---

### 14.5 Test Case 5 — Grand Total Skor Prodi

**Skenario:** 7 karakteristik, semua berstatus `diterima`.

| Karakteristik    | Skor | Bobot | Nilai Tertimbang |
| ---------------- | ---- | ----- | ---------------- |
| Program Outcomes | 60%  | 15%   | 9.00             |
| Curriculum       | 65%  | 20%   | 13.00            |
| Teaching         | 80%  | 15%   | 12.00            |
| Assessment       | 70%  | 15%   | 10.50            |
| Facilities       | 72%  | 10%   | 7.20             |
| Research         | 64%  | 10%   | 6.40             |
| Alumni           | 68%  | 15%   | 10.20            |

**Perhitungan Manual:**

```
Grand Total = 9.00 + 13.00 + 12.00 + 10.50 + 7.20 + 6.40 + 10.20
            = 68.30%
```

**Expected Output:**

```
Skor Total     : 68.30%
Status         : ALMOST COMPLIANT
Gap ke standar : 75.00 - 68.30 = 6.70 poin
Karakteristik kritis: Research (64%), Alumni (68%)
```

**Verifikasi Database:**

```sql
SELECT SUM(s.skor * k.bobot) / 100 AS grand_total
FROM submission s
JOIN kriteria k ON k.kriteria_id = s.kriteria_id
WHERE s.prodi_id = 1 AND s.status = 'diterima';
-- Expected result: 68.30
```

---

### 14.6 Test Case 6 — Konsistensi Skor (Re-verification)

**Skenario:** Validator memverifikasi ulang bahwa skor yang tersimpan konsisten dengan data item.

| Langkah     | Aksi                                          | Expected                              |
| ----------- | --------------------------------------------- | ------------------------------------- |
| 1           | Baca skor tersimpan dari `submission.skor`    | `60.00`                               |
| 2           | Recalculate ulang via `SkorService::hitung()` | `60.00`                               |
| 3           | Bandingkan hasil                              | `stored == recalculated` → CONSISTENT |
| 4 (anomali) | Jika berbeda                                  | Log warning, flag di admin dashboard  |

**Assertion:**

```php
$stored       = $submission->skor;
$recalculated = $skorService->hitung($submission->submission_id);
$this->assertEquals($stored, $recalculated, 0.01); // toleransi 0.01
```

---

### 14.7 Ringkasan Testing Checklist

**Unit Tests (Backend):**

- [ ] `SkorService::hitung()` menghasilkan persentase benar untuk semua tipe item
- [ ] `SkorService::hitungGrandTotal()` menghasilkan weighted average yang benar
- [ ] Validasi tipe `numerik` menolak nilai di bawah `nilai_min_numerik`
- [ ] Validasi format file upload (PDF, DOCX, XLSX)
- [ ] Validasi ukuran file upload (maks 10MB)
- [ ] Transition status submission mengikuti state machine
- [ ] Komentar validator wajib saat status `revisi` atau `ditolak`

**Integration Tests:**

- [ ] Alur lengkap: dosen submit → validator review → skor tersimpan di laporan
- [ ] Upload file → dokumen tercatat → skor submission naik
- [ ] Generate laporan PDF berhasil dan hanya mengambil submission `diterima`

**UI / E2E Tests:**

- [ ] Progress bar skor update real-time tanpa reload
- [ ] Tombol Submit disabled jika skor < 50%
- [ ] Preview dokumen terbuka di browser (PDF viewer)
- [ ] Validator antrian hanya menampilkan submission `submitted`
- [ ] Gap analysis tampil benar di halaman laporan

---

## 15. Validasi Input & Edge Cases

### 15.1 Aturan Validasi per Tipe Item

| Tipe        | Aturan Validasi                                            | Pesan Error                                      |
| ----------- | ---------------------------------------------------------- | ------------------------------------------------ |
| `checklist` | Boolean only (true/false)                                  | —                                                |
| `upload`    | Format: PDF, DOCX, XLSX; Maks: 10 MB; Maks 5 file per item | "Format tidak didukung" / "Ukuran melebihi 10MB" |
| `numerik`   | Harus angka; range ≥ `nilai_min_numerik` jika diset        | "Input harus angka" / "Nilai minimal adalah X"   |
| `narasi`    | Teks bebas; min 0, maks 5000 karakter                      | "Teks terlalu panjang (maks 5000 karakter)"      |

**Aturan Konfigurasi Template (Admin):**

- Total bobot item per sub-kriteria **harus = 100**. Sistem menolak jika tidak terpenuhi.
- Item `narasi` **wajib bobot = 0**.
- Minimal 1 item wajib (`wajib = TRUE`) per sub-kriteria.

### 15.2 Penanganan Edge Cases

**Edge Case A: Upload File Duplikat (nama sama)**

- Sistem menyimpan kedua file dengan nama berbeda (append timestamp: `file_20260402T143000.pdf`)
- UI menampilkan keduanya; dosen bisa hapus yang lama secara manual
- Tidak ada auto-replace

**Edge Case B: Submission Direvisi Setelah `diterima`**

- Status `diterima` mengunci submission dari pengeditan
- Untuk membuka kembali: admin ubah status ke `draft` via admin panel
- Setiap perubahan status tercatat di audit log (future: tabel `audit_log`)

**Edge Case C: Generate Laporan dengan Submission Belum Selesai**

- Sistem tetap bisa generate laporan (tidak diblokir)
- PDF menyertakan catatan: "N karakteristik masih pending review / dalam draft"
- Skor total hanya menghitung yang berstatus `diterima`

**Edge Case D: Validator Review Submission yang Sudah Direvisi Ulang**

- Jika dosen sudah re-submit setelah revisi, antrian validator menampilkan versi terbaru
- Versi lama (sebelum re-submit) tidak muncul di antrian
- Validator tidak bisa review versi lama

**Edge Case E: Dua Dosen Edit Submission Bersamaan**

- Sistem menggunakan database-level locking pada tabel `submission_item`
- Jika terjadi konflik: request yang belakangan mendapat respons error `409 Conflict`
- Dosen diminta reload dan coba lagi

**Edge Case F: File Dokumen Dihapus Manual dari Storage**

- Tabel `dokumen` masih mencatat referensi file
- Saat preview: sistem menampilkan pesan "File tidak ditemukan"
- Admin dapat menjalankan perintah `php artisan storage:audit` untuk mendeteksi dan membersihkan referensi orphan

---

## 16. Kriteria Penerimaan

- [ ] Admin dapat menambah karakteristik dan sub-karakteristik IABEE
- [ ] Admin dapat mengkonfigurasi template item per sub-karakteristik (bobot total harus 100)
- [ ] Dosen dapat melihat daftar karakteristik beserta status dan skor progresnya
- [ ] Dosen dapat mengisi template dan menyimpan sebagai draft
- [ ] Dosen dapat mengupload file dokumen per item tipe `upload`
- [ ] Dosen dapat mengsubmit dan melihat hasil validasi + komentar validator
- [ ] Skor submission diperbarui secara real-time saat dosen mengisi item
- [ ] Tombol Submit disabled jika skor sementara < 50%
- [ ] Validator dapat melihat antrian submission dan melakukan review
- [ ] Validator wajib mengisi komentar saat memberi status revisi/tolak
- [ ] Laporan PDF dapat di-generate dan mencantumkan grafik radar
- [ ] Laporan hanya menghitung submission yang telah disetujui validator
- [ ] Gap analysis ditampilkan pada halaman laporan
- [ ] Skor grand total menggunakan formula weighted average berdasarkan bobot karakteristik
- [ ] Semua test case pada Bagian 14 lulus dengan hasil yang sesuai expected output

---

## 17. Pertanyaan Klarifikasi untuk Stakeholder

Poin-poin berikut membutuhkan konfirmasi dari stakeholder IABEE / pemilik produk sebelum development dimulai:

| No. | Pertanyaan                                                                  | Opsi                           | Status |
| --- | --------------------------------------------------------------------------- | ------------------------------ | ------ |
| 1   | Berapa threshold minimum overall untuk status "COMPLIANT"?                  | 75% / 80% / Custom             | ❓     |
| 2   | Apakah threshold per karakteristik sama semua atau berbeda?                 | Sama semua / Per karakteristik | ❓     |
| 3   | Apakah bobot karakteristik fixed by system atau customizable per prodi?     | Fixed / Custom per prodi       | ❓     |
| 4   | Berapa batas minimum skor untuk tombol Submit diaktifkan?                   | 50% / 60% / Tidak ada          | ❓     |
| 5   | Apakah upload dokumen langsung terpenuhi atau perlu validator approve dulu? | Auto / Butuh validator         | ❓     |
| 6   | Jika submission di-`tolak`, apakah data item ter-reset atau tersimpan?      | Reset / Tetap ada              | ❓     |
| 7   | Apakah ada deadline pengisian per prodi atau per karakteristik?             | Ya / Tidak                     | ❓     |
| 8   | Berapa banyak karakteristik utama IABEE 2026 yang perlu didukung?           | N karakteristik                | ❓     |

---

_Dokumen ini telah diperbarui ke versi 2.0.0 dan siap digunakan sebagai acuan development. Perbarui tanda ❓ pada Bagian 17 setelah konfirmasi stakeholder diperoleh._
