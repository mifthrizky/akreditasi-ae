# Product Requirements Document (PRD)

## Sistem Pedoman Kurikulum Akreditasi IABEE 2026

**Versi:** 3.0.0  
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
10. [Inventarisasi Halaman & Route](#10-inventarisasi-halaman--route)
11. [API Endpoint](#11-api-endpoint)
12. [Aturan Bisnis](#12-aturan-bisnis)
13. [Desain Awal — Wireframe & Alur I/O](#13-desain-awal--wireframe--alur-io)
14. [Rumus Perhitungan Skor](#14-rumus-perhitungan-skor)
15. [Verifikasi & Test Cases](#15-verifikasi--test-cases)
16. [Validasi Input & Edge Cases](#16-validasi-input--edge-cases)
17. [Kriteria Penerimaan](#17-kriteria-penerimaan)
18. [Pertanyaan Klarifikasi untuk Stakeholder](#18-pertanyaan-klarifikasi-untuk-stakeholder)

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
- Laporan kesiapan prodi dengan grafik radar dan gap analysis

### 6.3 Validator

- Dashboard antrian submission yang menunggu review
- Preview dokumen yang diupload langsung di browser
- Memberi status: disetujui / revisi / ditolak + komentar wajib saat revisi/tolak
- Riwayat validasi yang pernah dilakukan, dapat difilter per status dan rentang tanggal

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
-- level 1 = sub-grup kriteria (pengelompok, tidak punya template)
-- level 2 = sub-kriteria (punya template & submission)
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
│   │   │   ├── DashboardController.php      ← BARU
│   │   │   ├── KriteriaController.php
│   │   │   ├── TemplateItemController.php
│   │   │   ├── ProgramStudiController.php
│   │   │   └── UserController.php
│   │   ├── Dosen/
│   │   │   ├── DashboardController.php      ← BARU
│   │   │   ├── SubmissionController.php
│   │   │   ├── SubmissionItemController.php
│   │   │   ├── DokumenController.php
│   │   │   └── LaporanController.php        ← BARU
│   │   ├── Validator/
│   │   │   ├── DashboardController.php      ← BARU
│   │   │   ├── ValidasiController.php
│   │   │   └── RiwayatController.php        ← BARU
│   │   └── Auth/
│   │       └── LoginController.php          ← BARU
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
    ├── SkorService.php
    └── LaporanService.php

resources/views/
├── auth/
│   └── login.blade.php
├── admin/
│   ├── dashboard.blade.php
│   ├── users/
│   │   ├── index.blade.php
│   │   └── form.blade.php
│   ├── prodi/
│   │   ├── index.blade.php
│   │   └── form.blade.php
│   ├── kriteria/
│   │   ├── index.blade.php
│   │   └── form.blade.php
│   └── template/
│       └── index.blade.php
├── dosen/
│   ├── dashboard.blade.php
│   ├── prodi/
│   │   └── show.blade.php
│   ├── submission/
│   │   ├── form.blade.php
│   │   └── review.blade.php
│   └── laporan/
│       └── show.blade.php
└── validator/
    ├── dashboard.blade.php
    ├── antrian/
    │   └── index.blade.php
    ├── review/
    │   └── show.blade.php
    └── riwayat/
        └── index.blade.php
```

---

## 10. Inventarisasi Halaman & Route

Bagian ini mendokumentasikan **seluruh halaman** yang akan dibangun, dikelompokkan per role. Setiap halaman dilengkapi route, controller method, konten utama, dan catatan tambahan.

### 10.0 Halaman Bersama (Semua Role)

#### `GET /login` — Halaman Login

- **Controller:** `Auth\LoginController@showForm`
- **Konten:** Form email + password, tombol Login
- **Redirect setelah login:**
    - Admin → `/admin/dashboard`
    - Dosen → `/dosen/dashboard`
    - Validator → `/validator/dashboard`
- **Catatan:** Jika sudah login, redirect langsung ke dashboard role masing-masing. Gunakan Laravel Breeze / Sanctum.

---

### 10.1 Admin

#### `GET /admin/dashboard` — Dashboard Admin

- **Controller:** `Admin\DashboardController@index`
- **Konten:**
    - Ringkasan angka: jumlah prodi aktif, total submission masuk, submission menunggu validasi, total user
    - Tabel progres per prodi: nama prodi, jumlah karakteristik selesai / total, skor rata-rata, status keseluruhan
    - Shortcut cepat: Kelola User, Kelola Prodi, Kelola Kriteria
- **Query utama:**
    ```sql
    SELECT p.nama, COUNT(s.submission_id) AS total,
           SUM(CASE WHEN s.status = 'diterima' THEN 1 ELSE 0 END) AS selesai,
           AVG(s.skor) AS rata_skor
    FROM program_studi p
    LEFT JOIN submission s ON s.prodi_id = p.prodi_id
    GROUP BY p.prodi_id;
    ```

---

#### `GET /admin/users` — Daftar User

- **Controller:** `Admin\UserController@index`
- **Konten:** Tabel semua user (nama, email, role, tanggal daftar), tombol Tambah, Edit, Hapus
- **Filter:** Berdasarkan role (semua / admin / dosen / validator)

#### `GET /admin/users/create` — Tambah User

- **Controller:** `Admin\UserController@create`
- **Konten:** Form nama, email, password, role

#### `GET /admin/users/{id}/edit` — Edit User

- **Controller:** `Admin\UserController@edit`
- **Konten:** Form yang sama dengan data user ter-prefill, tombol simpan

---

#### `GET /admin/prodi` — Daftar Program Studi

- **Controller:** `Admin\ProgramStudiController@index`
- **Konten:**
    - Tabel prodi: kode, nama, jurusan, jumlah dosen, jumlah validator, progres pengisian
    - Tombol: Tambah Prodi, Edit, Hapus, Assign User
- **Catatan:** Hapus prodi hanya boleh jika belum ada submission aktif (tampilkan konfirmasi jika ada)

#### `GET /admin/prodi/create` — Tambah Prodi

- **Controller:** `Admin\ProgramStudiController@create`
- **Konten:** Form kode, nama, jurusan (dropdown: ME / FE / DE / AE)

#### `GET /admin/prodi/{id}/edit` — Edit Prodi

- **Controller:** `Admin\ProgramStudiController@edit`
- **Konten:** Form prodi ter-prefill

#### `GET /admin/prodi/{id}/assign` — Assign User ke Prodi ← **HALAMAN TAMBAHAN**

- **Controller:** `Admin\ProgramStudiController@assignForm`
- **Konten:**
    - Nama prodi di header
    - Daftar dosen yang sudah di-assign (dengan tombol Lepas)
    - Daftar validator yang sudah di-assign (dengan tombol Lepas)
    - Dropdown / multi-select untuk menambah dosen baru ke prodi ini
    - Dropdown / multi-select untuk menambah validator baru ke prodi ini
- **Catatan:** Satu user bisa di-assign ke lebih dari satu prodi. Relasi dijaga via tabel `user_prodi`.

---

#### `GET /admin/kriteria` — Daftar Kriteria & Sub-kriteria

- **Controller:** `Admin\KriteriaController@index`
- **Konten:**
    - Tampilan hierarki: kriteria utama (level 0) dapat di-expand untuk melihat sub-kriteria (level 1)
    - Tiap kriteria utama: kode, nama, bobot, jumlah sub-kriteria
    - Tiap sub-kriteria: kode, nama, bobot, jumlah template item, tombol Konfigurasi Template
    - Tombol: Tambah Kriteria Utama, Tambah Sub-kriteria (dari dalam baris kriteria utama), Edit, Hapus

#### `GET /admin/kriteria/create` — Tambah Kriteria

- **Controller:** `Admin\KriteriaController@create`
- **Konten:** Form kode, nama, deskripsi, level (0 atau 1), parent (jika level 1), bobot, urutan

#### `GET /admin/kriteria/{id}/edit` — Edit Kriteria

- **Controller:** `Admin\KriteriaController@edit`
- **Konten:** Form kriteria ter-prefill

---

#### `GET /admin/kriteria/{id}/template` — Konfigurasi Template Sub-kriteria

- **Controller:** `Admin\TemplateItemController@index`
- **Konten:**
    - Header: nama sub-kriteria, total bobot saat ini (harus = 100)
    - Tabel item template: label, tipe, bobot, wajib, nilai minimum (jika numerik), urutan
    - Indikator validasi bobot: jika total ≠ 100 tampilkan peringatan merah
    - Tombol: Tambah Item, Edit Item, Hapus Item, Ubah Urutan (drag-and-drop atau panah atas/bawah)
- **Catatan:** Sistem memblokir simpan jika total bobot item ≠ 100

#### `GET /admin/kriteria/{id}/template/create` — Tambah Item Template ← **HALAMAN TAMBAHAN**

- **Controller:** `Admin\TemplateItemController@create`
- **Konten:** Form label, tipe (checklist / upload / numerik / narasi), bobot, hint, wajib (toggle), nilai minimum (tampil jika tipe = numerik), urutan
- **Validasi frontend:** preview akumulasi bobot real-time saat user mengisi angka bobot

#### `GET /admin/kriteria/{id}/template/{item_id}/edit` — Edit Item Template ← **HALAMAN TAMBAHAN**

- **Controller:** `Admin\TemplateItemController@edit`
- **Konten:** Form yang sama dengan data item ter-prefill

---

### 10.2 Dosen

#### `GET /dosen/dashboard` — Dashboard Dosen

- **Controller:** `Dosen\DashboardController@index`
- **Konten:**
    - Daftar program studi yang diassign ke dosen ini
    - Tiap prodi: nama, kode, mini progress bar (berapa sub-kriteria selesai / total), skor rata-rata sementara
    - Klik prodi → masuk ke `/dosen/prodi/{id}`
- **Catatan:** Jika dosen hanya di-assign ke satu prodi, redirect langsung ke `/dosen/prodi/{id}` tanpa perlu halaman dashboard perantara

---

#### `GET /dosen/prodi/{id}` — Daftar Kriteria Prodi

- **Controller:** `Dosen\SubmissionController@kriteriaIndex`
- **Konten:**
    - Header prodi: nama, kode, jurusan, progress keseluruhan
    - Filter status: Semua / Draft / Submitted / Diterima / Revisi / Ditolak / Belum Dimulai
    - Daftar kriteria utama yang dapat di-expand, berisi sub-kriteria dengan:
        - Badge status submission (warna per status)
        - Skor sementara (jika sudah ada submission)
        - Tanggal terakhir diperbarui
        - Tombol aksi: Mulai Isi / Lanjut Isi / Lihat Detail / Lihat Review
    - Ringkasan bawah: [Generate Laporan Progres] [Lihat Laporan Resmi]

---

#### `GET /dosen/submission/{kriteria_id}` — Form Pengisian Sub-kriteria

- **Controller:** `Dosen\SubmissionController@form`
- **Konten:**
    - Panel kiri (utama): form dinamis sesuai template — checklist, input numerik, textarea narasi, area upload file
    - Panel kanan (sticky): skor sementara real-time, jumlah item terpenuhi, badge status, tombol Simpan Draft dan Submit
    - Panel bawah: riwayat review jika pernah divalidasi (komentar validator + tanggal + status)
- **Catatan:** Jika submission belum ada, otomatis dibuat saat dosen pertama kali membuka halaman ini (status = `draft`)

---

#### `GET /dosen/submission/{id}/review` — Hasil Validasi & Revisi

- **Controller:** `Dosen\SubmissionController@review`
- **Konten:**
    - Status submission saat ini (badge besar)
    - Komentar validator terbaru beserta tanggal dan nama validator
    - Tampilan read-only semua item yang pernah diisi
    - Jika status = `revisi`: tombol [Perbaiki Submission] yang membuka kembali form edit
    - Riwayat semua validasi sebelumnya (jika pernah revisi berkali-kali)
- **Catatan:** Jika status bukan `revisi`, halaman ini hanya tampilan, tidak ada aksi edit

---

#### `GET /dosen/prodi/{id}/laporan` — Laporan Kesiapan Prodi

- **Controller:** `Dosen\LaporanController@show`
- **Konten:**
    - Skor total prodi (weighted average, hanya submission `diterima`)
    - Badge status kesiapan (FULLY COMPLIANT / COMPLIANT / ALMOST COMPLIANT / NOT COMPLIANT)
    - Grafik radar / spider chart: skor per kriteria vs standar minimum IABEE
    - Tabel skor per kriteria dengan indikator warna (hijau/kuning/merah)
    - Gap analysis: daftar karakteristik di bawah standar beserta selisih poin
    - Daftar submission yang belum `diterima` (progress belum final)
    - Histori laporan PDF yang pernah di-generate (tanggal, skor saat itu, link download)
    - Tombol [Generate Laporan PDF Resmi] — hanya aktif jika ada minimal 1 submission `diterima`
- **Catatan:** Skor pada halaman ini bersifat live (recalculate setiap kali dibuka). PDF yang di-generate adalah snapshot statis.

---

### 10.3 Validator

#### `GET /validator/dashboard` — Dashboard Validator

- **Controller:** `Validator\DashboardController@index`
- **Konten:**
    - Ringkasan angka hari ini: submission menunggu review, submission diselesaikan hari ini, total disetujui, total dikembalikan
    - Breakdown antrian per prodi: nama prodi, jumlah submission menunggu
    - 5 submission terbaru yang masuk antrian (sebagai shortcut)
    - Tombol [Lihat Semua Antrian]
- **Catatan:** Validator hanya melihat submission dari prodi yang ia di-assign

---

#### `GET /validator/antrian` — Antrian Review

- **Controller:** `Validator\ValidasiController@antrian`
- **Konten:**
    - Filter: prodi (dropdown), kriteria (dropdown), rentang tanggal submit
    - Tabel antrian: nama prodi, kode sub-kriteria, nama dosen yang submit, tanggal submit, skor sementara
    - Urutan default: terlama dulu (FIFO)
    - Tombol [Review] per baris → ke `/validator/review/{submission_id}`
- **Catatan:** Hanya menampilkan submission berstatus `submitted`

---

#### `GET /validator/review/{submission_id}` — Review Detail Submission

- **Controller:** `Validator\ValidasiController@show`
- **Konten:**
    - Panel kiri: semua jawaban dosen — tiap item tampil dengan label, jawaban, dan kontribusi bobot
    - Daftar dokumen: nama file, ukuran, tombol [Preview] (buka di browser) dan [Download]
    - Catatan dosen (narasi) jika ada
    - Skor sementara submission
    - Panel kanan: form keputusan — radio Setujui / Revisi / Tolak, textarea komentar (wajib jika Revisi/Tolak), tombol [Submit Keputusan]
    - Panel bawah: riwayat validasi sebelumnya untuk submission ini (jika pernah di-revisi)
- **Catatan:** Setelah submit keputusan, redirect kembali ke `/validator/antrian` dengan notifikasi sukses

---

#### `GET /validator/riwayat` — Riwayat Validasi

- **Controller:** `Validator\RiwayatController@index`
- **Konten:**
    - Filter: status (disetujui / revisi / ditolak), prodi, rentang tanggal
    - Tabel riwayat: nama prodi, kode sub-kriteria, nama dosen, tanggal divalidasi, status keputusan, tombol [Lihat Detail]
    - Klik [Lihat Detail] → ke `/validator/review/{submission_id}` dalam mode read-only
- **Catatan:** Hanya menampilkan validasi yang pernah dilakukan oleh validator yang sedang login

---

### 10.4 Halaman Tambahan yang Diperlukan

Berikut halaman-halaman yang **belum** tercakup dalam rencana awal namun diperlukan agar sistem berjalan lengkap:

| No. | Route                                           | Deskripsi                                              | Prioritas |
| --- | ----------------------------------------------- | ------------------------------------------------------ | --------- |
| 1   | `GET /login`                                    | Halaman login, redirect per role                       | Wajib     |
| 2   | `POST /logout`                                  | Proses logout, hapus session                           | Wajib     |
| 3   | `GET /admin/prodi/{id}/assign`                  | Halaman assign dosen & validator ke prodi              | Wajib     |
| 4   | `GET /admin/kriteria/{id}/template/create`      | Form tambah item template                              | Wajib     |
| 5   | `GET /admin/kriteria/{id}/template/{item}/edit` | Form edit item template                                | Wajib     |
| 6   | `GET /admin/users/create`                       | Form tambah user baru                                  | Wajib     |
| 7   | `GET /admin/users/{id}/edit`                    | Form edit data user                                    | Wajib     |
| 8   | `GET /admin/kriteria/create`                    | Form tambah kriteria / sub-kriteria                    | Wajib     |
| 9   | `GET /admin/kriteria/{id}/edit`                 | Form edit kriteria                                     | Wajib     |
| 10  | `GET /admin/prodi/create`                       | Form tambah prodi baru                                 | Wajib     |
| 11  | `GET /admin/prodi/{id}/edit`                    | Form edit prodi                                        | Wajib     |
| 12  | `GET /dosen/submission/{id}/review`             | Halaman lihat hasil validasi & revisi                  | Wajib     |
| 13  | `GET /validator/review/{id}` mode read-only     | Lihat detail dari halaman riwayat                      | Wajib     |
| 14  | `GET /`                                         | Root redirect: cek session → arahkan ke dashboard role | Wajib     |

---

## 11. API Endpoint

Endpoint berikut melayani interaksi AJAX (real-time scoring, upload file, dll.) di samping route halaman standar Laravel Blade.

| Method | Endpoint                              | Role        | Deskripsi                                  |
| ------ | ------------------------------------- | ----------- | ------------------------------------------ |
| GET    | `/admin/kriteria`                     | admin       | Daftar semua kriteria                      |
| POST   | `/admin/kriteria`                     | admin       | Tambah kriteria baru                       |
| PUT    | `/admin/kriteria/{id}`                | admin       | Edit kriteria                              |
| DELETE | `/admin/kriteria/{id}`                | admin       | Hapus kriteria                             |
| POST   | `/admin/kriteria/{id}/template`       | admin       | Tambah item template                       |
| PUT    | `/admin/template/{item_id}`           | admin       | Edit item template                         |
| DELETE | `/admin/template/{item_id}`           | admin       | Hapus item template                        |
| POST   | `/admin/prodi/{id}/assign`            | admin       | Assign user ke prodi                       |
| DELETE | `/admin/prodi/{id}/assign/{user_id}`  | admin       | Lepas user dari prodi                      |
| GET    | `/dosen/prodi/{id}/kriteria`          | dosen       | Daftar kriteria + status per prodi         |
| GET    | `/dosen/submission/{id}`              | dosen       | Detail submission dan item-nya             |
| POST   | `/dosen/submission`                   | dosen       | Buat submission baru (draft)               |
| PATCH  | `/dosen/submission-item/{id}`         | dosen       | Update satu item — memicu recalculate skor |
| POST   | `/dosen/submission/{id}/submit`       | dosen       | Submit untuk validasi                      |
| POST   | `/dosen/submission-item/{id}/dokumen` | dosen       | Upload dokumen                             |
| DELETE | `/dosen/dokumen/{id}`                 | dosen       | Hapus file dokumen                         |
| GET    | `/validator/antrian`                  | validator   | Daftar submission menunggu review          |
| POST   | `/validator/validasi/{submission_id}` | validator   | Setujui / revisi / tolak submission        |
| GET    | `/laporan/{prodi_id}`                 | semua       | Lihat data laporan progres prodi           |
| POST   | `/laporan/{prodi_id}/generate`        | dosen/admin | Generate laporan PDF resmi                 |
| GET    | `/laporan/{prodi_id}/histori`         | semua       | Daftar PDF yang pernah di-generate         |

---

## 12. Aturan Bisnis

1. **Template hanya untuk sub-kriteria** — `template_item` hanya boleh berelasi ke `kriteria` dengan `level = 2`. Kriteria utama dan grup (`level = 0` dan `level = 1`) hanya sebagai pengelompok.
2. **Satu submission per sub-kriteria per prodi** — Dijaga oleh `UNIQUE (prodi_id, kriteria_id)` di tabel `submission`.
3. **Skor dihitung otomatis** — Saat dosen mengisi `submission_item`, sistem menghitung skor dari bobot item yang terpenuhi. Skor disimpan di kolom `skor` tabel `submission`.
4. **Laporan resmi hanya dari submission `diterima`** — Saat generate laporan, hanya submission dengan status `diterima` yang dihitung ke skor total.
5. **Validator wajib isi komentar saat revisi/tolak** — Validasi pada status `revisi` atau `ditolak` wajib mengisi kolom `komentar`.
6. **Kedalaman hierarki 3 level** — Level 0 (kriteria utama), level 1 (sub-grup kriteria), dan level 2 (sub-kriteria). Template dimasukkan ke level 2.
7. **Bobot item wajib total 100%** — Seluruh `bobot` dari `template_item` per sub-kriteria harus menjumlah tepat 100. Sistem menolak konfigurasi template jika total bobot tidak 100.
8. **Upload dokumen otomatis dihitung terpenuhi** — Ketika file berhasil diunggah, item tipe `upload` langsung dianggap terpenuhi untuk kalkulasi skor sementara. Namun validator tetap dapat menolak atau meminta perbaikan file.
9. **Submission yang sudah `diterima` terkunci** — Dosen tidak dapat mengubah isi submission berstatus `diterima` tanpa persetujuan admin. Admin dapat membuka kunci dengan mengubah status kembali ke `draft` beserta audit log.
10. **Nilai numerik wajib memenuhi nilai minimum** — Item bertipe `numerik` dengan `nilai_min_numerik` tidak null hanya dianggap terpenuhi jika nilai yang diinput ≥ `nilai_min_numerik`.
11. **Validator hanya melihat prodi yang di-assign** — Antrian dan riwayat validator hanya menampilkan submission dari prodi yang ia terdaftar di tabel `user_prodi`.
12. **Dosen hanya mengisi prodi yang di-assign** — Dosen tidak bisa membuka form submission prodi lain di luar `user_prodi` miliknya.

---

## 13. Desain Awal — Wireframe & Alur I/O

### 13.1 Peta Halaman Lengkap

```
[ / ] ──→ cek session ──→ redirect berdasarkan role
              │
              ├──→ [ LOGIN  /login ]
              │
              ├──→ ADMIN
              │     ├── /admin/dashboard          ← Ringkasan sistem
              │     ├── /admin/users              ← Daftar user
              │     │     ├── /create             ← Form tambah user
              │     │     └── /{id}/edit          ← Form edit user
              │     ├── /admin/prodi              ← Daftar prodi
              │     │     ├── /create             ← Form tambah prodi
              │     │     ├── /{id}/edit          ← Form edit prodi
              │     │     └── /{id}/assign        ← Assign user ke prodi
              │     └── /admin/kriteria           ← Hierarki kriteria
              │           ├── /create             ← Form tambah kriteria
              │           ├── /{id}/edit          ← Form edit kriteria
              │           └── /{id}/template      ← Konfigurasi template
              │                 ├── /create       ← Form tambah item
              │                 └── /{item}/edit  ← Form edit item
              │
              ├──→ DOSEN
              │     ├── /dosen/dashboard          ← Daftar prodi dosen
              │     ├── /dosen/prodi/{id}         ← Daftar kriteria prodi
              │     ├── /dosen/submission/{krit}  ← Form pengisian
              │     ├── /dosen/submission/{id}/review ← Hasil validasi
              │     └── /dosen/prodi/{id}/laporan ← Laporan kesiapan
              │
              └──→ VALIDATOR
                    ├── /validator/dashboard      ← Ringkasan antrian
                    ├── /validator/antrian        ← List antrian review
                    ├── /validator/review/{id}    ← Review detail submission
                    └── /validator/riwayat        ← Riwayat validasi
```

---

### 13.2 Wireframe — Halaman Login

```
┌─────────────────────────────────────────────────────────┐
│                                                         │
│              IABEE Curriculum Checker                   │
│              Sistem Kesiapan Akreditasi 2026            │
│                                                         │
│         ┌───────────────────────────────────┐          │
│         │  Email                            │          │
│         │  [________________________________] │          │
│         │                                   │          │
│         │  Password                         │          │
│         │  [________________________________] │          │
│         │                                   │          │
│         │  [         MASUK           ]      │          │
│         └───────────────────────────────────┘          │
│                                                         │
│         Hubungi admin jika lupa password.               │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

---

### 13.3 Wireframe — Admin Dashboard

```
┌─────────────────────────────────────────────────────────┐
│  IABEE Checker  [Prodi] [User] [Kriteria]   Admin ▾    │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  RINGKASAN SISTEM                                       │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐  │
│  │  4       │ │  47      │ │  12      │ │  8       │  │
│  │  Prodi   │ │Submission│ │ Menunggu │ │  User    │  │
│  │  Aktif   │ │  Masuk   │ │ Review   │ │  Total   │  │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘  │
│                                                         │
│  PROGRESS PER PRODI                   [+ Tambah Prodi]  │
│  ┌─────────────────────────────────────────────────┐   │
│  │ Teknik Informatika  │ 5/7 selesai │ Skor: 74% ⚠ │   │
│  │ [███████████████░░] 71%           │ [Detail]    │   │
│  ├─────────────────────────────────────────────────┤   │
│  │ Teknik Sipil        │ 7/7 selesai │ Skor: 82% ✓ │   │
│  │ [████████████████████] 100%       │ [Detail]    │   │
│  ├─────────────────────────────────────────────────┤   │
│  │ Teknik Mesin        │ 3/7 selesai │ Skor: 61% ❌│   │
│  │ [████████░░░░░░░░░░] 43%          │ [Detail]    │   │
│  ├─────────────────────────────────────────────────┤   │
│  │ Desain Engineering  │ 1/7 selesai │ Skor: 45% ❌│   │
│  │ [████░░░░░░░░░░░░░░] 14%          │ [Detail]    │   │
│  └─────────────────────────────────────────────────┘   │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

---

### 13.4 Wireframe — Admin Manajemen User

```
┌─────────────────────────────────────────────────────────┐
│  [← Dashboard]   Manajemen User          [+ Tambah]    │
├─────────────────────────────────────────────────────────┤
│  Filter: [Semua Role ▾]   Cari: [________________]      │
│                                                         │
│  ┌──────────────────────────────────────────────────┐  │
│  │ Nama            │ Email         │ Role      │ Aksi│  │
│  ├──────────────────────────────────────────────────┤  │
│  │ Dr. Budi S.     │ budi@uni.ac   │ Dosen     │ ✎ 🗑│  │
│  │ Dr. Siti R.     │ siti@uni.ac   │ Validator │ ✎ 🗑│  │
│  │ Prof. Ahmad     │ ahmad@uni.ac  │ Dosen     │ ✎ 🗑│  │
│  │ Admin Sistem    │ admin@uni.ac  │ Admin     │ ✎   │  │
│  └──────────────────────────────────────────────────┘  │
│  Menampilkan 1–4 dari 8 user                            │
└─────────────────────────────────────────────────────────┘
```

---

### 13.5 Wireframe — Admin Assign User ke Prodi

```
┌─────────────────────────────────────────────────────────┐
│  [← Daftar Prodi]   Assign User: Teknik Informatika    │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  DOSEN YANG DI-ASSIGN                                   │
│  ┌─────────────────────────────────────────────────┐   │
│  │ Dr. Budi Santoso    budi@uni.ac    [Lepas]      │   │
│  │ Prof. Rina Kurnia   rina@uni.ac    [Lepas]      │   │
│  └─────────────────────────────────────────────────┘   │
│  Tambah Dosen: [Pilih Dosen ▾]  [+ Tambahkan]          │
│                                                         │
│  VALIDATOR YANG DI-ASSIGN                               │
│  ┌─────────────────────────────────────────────────┐   │
│  │ Dr. Siti Rahayu     siti@uni.ac    [Lepas]      │   │
│  └─────────────────────────────────────────────────┘   │
│  Tambah Validator: [Pilih Validator ▾]  [+ Tambahkan]   │
│                                                         │
│  [Selesai]                                              │
└─────────────────────────────────────────────────────────┘
```

---

### 13.6 Wireframe — Admin Konfigurasi Template

```
┌─────────────────────────────────────────────────────────┐
│  [← Kriteria]   Template: 1.1 Program Outcomes (PO)    │
├─────────────────────────────────────────────────────────┤
│  Total Bobot: 100% ✅   Wajib diisi sebelum development │
│                                                 [+ Item] │
│  ┌──────────────────────────────────────────────────┐  │
│  │ ↕ │ Label                │ Tipe      │ Bobot│ W │Aksi│
│  ├──────────────────────────────────────────────────┤  │
│  │ ↕ │ PO1: Analisis masalah│ checklist │  20% │ ✓ │✎ 🗑│
│  │ ↕ │ PO2: Design solusi   │ checklist │  20% │ ✓ │✎ 🗑│
│  │ ↕ │ PO3: Komunikasi      │ checklist │  20% │ ✓ │✎ 🗑│
│  │ ↕ │ PO4: Kerja tim       │ checklist │  20% │ ✓ │✎ 🗑│
│  │ ↕ │ PO5: Sosial & etika  │ checklist │  20% │ ✓ │✎ 🗑│
│  │ ↕ │ Catatan tambahan     │ narasi    │   0% │ - │✎ 🗑│
│  └──────────────────────────────────────────────────┘  │
│  Akumulasi bobot (wajib): 100%  ✅                      │
└─────────────────────────────────────────────────────────┘
```

---

### 13.7 Wireframe — Dosen Dashboard

```
┌─────────────────────────────────────────────────────────┐
│  IABEE Checker       [Notif 2]     Halo, Dr. Budi ▾    │
├─────────────────────────────────────────────────────────┤
│  Program Studi Saya                                     │
│  ┌─────────────────────────────────────────────────┐   │
│  │  Teknik Informatika — 2024                      │   │
│  │  Progress: 5/7 karakteristik disetujui          │   │
│  │  [████████████████░░] 71%   Skor: 74%           │   │
│  │  [Buka Prodi →]                                 │   │
│  ├─────────────────────────────────────────────────┤   │
│  │  Teknik Mesin — 2024                            │   │
│  │  Progress: 2/7 karakteristik disetujui          │   │
│  │  [██████░░░░░░░░░░░░] 29%   Skor: 61%           │   │
│  │  [Buka Prodi →]                                 │   │
│  └─────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘
```

---

### 13.8 Wireframe — Dosen Daftar Kriteria Prodi

```
┌─────────────────────────────────────────────────────────┐
│  [← Dashboard]   Teknik Informatika   Progress: 71%    │
├─────────────────────────────────────────────────────────┤
│  Filter: [Semua ▾]                  [Lihat Laporan]     │
│                                                         │
│  1. Program Outcomes                                    │
│     └─ 1.1 PO Alignment         ✅ DITERIMA  [75/100]  │
│     └─ 1.2 PO Assessment        ⏳ SUBMITTED [60/100]  │
│                                                         │
│  2. Curriculum Content                                  │
│     └─ 2.1 Curriculum Design    ✏️ DRAFT     [40/100]  │
│     └─ 2.2 Curriculum Review    ➖ BELUM      [–/100]  │
│                                                         │
│  3. Teaching & Learning Methods                         │
│     └─ 3.1 Teaching Strategy    🔄 REVISI    [70/100]  │
│        ⚠ Komentar: "Dokumen tidak lengkap"             │
│        [Lihat Review & Perbaiki]                        │
│     └─ 3.2 Learning Resources   ➖ BELUM      [–/100]  │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

---

### 13.9 Wireframe — Form Pengisian Sub-kriteria

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
│  Format: PDF/DOCX/XLSX │ Maks: 10MB/file     │  Syarat submit:  │
│  [📎 Pilih File]                             │  Skor ≥ 50%      │
│  • PO_Mapping_2024.pdf   2.1MB  ✅  [Hapus] │                  │
│  • Kurikulum_2024.docx   1.5MB  ✅  [Hapus] │  [SIMPAN DRAFT]  │
│                                              │  [SUBMIT ▶]      │
│  CATATAN DOSEN (Opsional):                   │                  │
│  ┌──────────────────────────────────────┐    │                  │
│  │ PO telah ditetapkan oleh tim ...    │    │                  │
│  └──────────────────────────────────────┘    │                  │
│                                              │                  │
│  RIWAYAT REVIEW:                            │                  │
│  2 Apr — Validator A: [REVISI]              │                  │
│  "Item PO4 belum ada bukti dokumen"         │                  │
└──────────────────────────────────────────────┴──────────────────┘
```

---

### 13.10 Wireframe — Dosen Hasil Validasi & Revisi

```
┌─────────────────────────────────────────────────────────┐
│  [← Daftar Kriteria]   Hasil Validasi: PO Alignment    │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  Status: 🔄 REVISI                                      │
│  Divalidasi: 3 Apr 2026 oleh Dr. Siti Rahayu           │
│                                                         │
│  Komentar Validator:                                    │
│  ┌─────────────────────────────────────────────────┐   │
│  │ Item PO4 (Kerja Tim) belum dilengkapi dengan    │   │
│  │ bukti dokumen. Mohon upload RPS atau catatan    │   │
│  │ kegiatan kelompok yang relevan.                 │   │
│  └─────────────────────────────────────────────────┘   │
│                                                         │
│  Isi Submission Anda:                                   │
│  ☑ PO1: Analisis masalah teknis — terpenuhi            │
│  ☑ PO2: Design solusi sistemik — terpenuhi             │
│  ☑ PO3: Komunikasi teknis — terpenuhi                  │
│  ☐ PO4: Kerja tim profesional — belum terpenuhi        │
│  ☐ PO5: Aspek sosial & etika — belum terpenuhi         │
│  Dokumen: PO_Mapping.pdf, Kurikulum_2024.docx          │
│                                                         │
│  Riwayat Sebelumnya:                                    │
│  • 30 Mar 2026 — Validator A: REVISI                   │
│    "Dokumen tidak terbaca, mohon upload ulang"          │
│                                                         │
│  [✎ Perbaiki Submission]                               │
└─────────────────────────────────────────────────────────┘
```

---

### 13.11 Wireframe — Laporan Kesiapan Prodi (Dosen)

```
┌─────────────────────────────────────────────────────────┐
│  [← Prodi]   Laporan Kesiapan: Teknik Informatika      │
├─────────────────────────────────────────────────────────┤
│  SKOR TOTAL: 68.3%  ⚠️ ALMOST COMPLIANT                 │
│  Standar IABEE: 75%  │  Gap: 6.7 poin                  │
│  [████████████████░░░░░░] 68.3%                         │
│  Catatan: 2 karakteristik masih pending / draft         │
├─────────────────────────────────────────────────────────┤
│  ┌──────────────────┐  ┌───────────────────────────┐   │
│  │  GRAFIK RADAR    │  │ DETAIL SKOR               │   │
│  │                  │  │ 1. PO           75%  ✅   │   │
│  │  [spider chart]  │  │ 2. Curriculum   85%  ✅   │   │
│  │  Prodi vs IABEE  │  │ 3. Teaching     80%  ✅   │   │
│  │                  │  │ 4. Assessment   70%  ⚠️   │   │
│  │                  │  │ 5. Research     64%  ❌   │   │
│  │                  │  │ 6. Alumni       68%  ⚠️   │   │
│  └──────────────────┘  └───────────────────────────┘   │
│                                                         │
│  GAP ANALYSIS                                           │
│  ❌ Research (64%)     — kurang 11 poin dari standar    │
│  ⚠️  Assessment (70%) — kurang 5 poin dari standar      │
│  ⚠️  Alumni (68%)     — kurang 7 poin dari standar      │
│                                                         │
│  PENDING (belum masuk perhitungan):                     │
│  • Teaching 3.2 — Draft  • Alumni 7.1 — Belum diisi    │
│                                                         │
│  HISTORI LAPORAN:                                       │
│  • 3 Apr 2026 — Skor: 65.2% — [Download PDF]          │
│  • 1 Apr 2026 — Skor: 58.0% — [Download PDF]          │
│                                                         │
│  [⬇ Generate Laporan PDF Resmi]                        │
└─────────────────────────────────────────────────────────┘
```

---

### 13.12 Wireframe — Validator Dashboard

```
┌─────────────────────────────────────────────────────────┐
│  IABEE Checker              Halo, Dr. Siti (Validator)  │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  RINGKASAN HARI INI                                     │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐  │
│  │  8       │ │  3       │ │  2       │ │  1       │  │
│  │ Menunggu │ │Selesai   │ │Disetujui │ │Dikembalikan│ │
│  │ Review   │ │ Hari Ini │ │ Hari Ini │ │ Hari Ini  │ │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘  │
│                                                         │
│  ANTRIAN PER PRODI                                      │
│  • Teknik Informatika — 4 submission menunggu          │
│  • Teknik Sipil       — 2 submission menunggu          │
│  • Teknik Mesin       — 2 submission menunggu          │
│                                                         │
│  TERBARU MASUK ANTRIAN:                                 │
│  ┌─────────────────────────────────────────────────┐   │
│  │ TI — PO Alignment    Dr. Budi     2 Apr, 14:30 │   │
│  │ TS — Teaching Method Prof. Rina   2 Apr, 10:15 │   │
│  │ TM — Assessment      Dr. Hendra   1 Apr, 16:45 │   │
│  └─────────────────────────────────────────────────┘   │
│  [Lihat Semua Antrian →]                                │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

---

### 13.13 Wireframe — Antrian Review Validator

```
┌─────────────────────────────────────────────────────────┐
│  [← Dashboard]   Antrian Review (8 menunggu)           │
├─────────────────────────────────────────────────────────┤
│  Filter:  Prodi: [Semua ▾]  Kriteria: [Semua ▾]        │
│           Tanggal: [__/__/____] s/d [__/__/____]        │
├─────────────────────────────────────────────────────────┤
│  ┌──────────────────────────────────────────────────┐  │
│  │ Prodi    │ Sub-kriteria    │ Dosen   │ Tgl Submit │  │
│  ├──────────────────────────────────────────────────┤  │
│  │ Tek.Info │ 1.1 PO Align.  │Dr. Budi │ 2 Apr 14:30│  │
│  │          │                │         │ [Review]   │  │
│  ├──────────────────────────────────────────────────┤  │
│  │ Tek.Sipil│ 3.1 Teaching   │Prof.Rina│ 2 Apr 10:15│  │
│  │          │                │         │ [Review]   │  │
│  ├──────────────────────────────────────────────────┤  │
│  │ Tek.Mesin│ 4.1 Assessment │Dr.Hendra│ 1 Apr 16:45│  │
│  │          │                │         │ [Review]   │  │
│  └──────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────┘
```

---

### 13.14 Wireframe — Review Detail Submission

```
┌─────────────────────────────────────────────────────────┐
│  [← Antrian]   Review: TI — 1.1 PO Alignment          │
├──────────────────────────────────┬──────────────────────┤
│  JAWABAN DOSEN                   │  KEPUTUSAN           │
│  Skor: 60%  Submitted: 2 Apr     │                      │
│                                  │  ○ ✅ SETUJUI        │
│  CHECKLIST:                      │  ○ 🔄 REVISI         │
│  ☑ PO1: Analisis (20%) ✓        │  ○ ❌ TOLAK          │
│  ☑ PO2: Design   (20%) ✓        │                      │
│  ☑ PO3: Komunik. (20%) ✓        │  Komentar            │
│  ☐ PO4: Kerja Tim(20%) ✗        │  (wajib jika         │
│  ☐ PO5: Sosial   (20%) ✗        │  revisi/tolak):      │
│                                  │  ┌────────────────┐  │
│  DOKUMEN UPLOAD:                 │  │                │  │
│  • PO_Mapping.pdf  [👁 Preview] │  │                │  │
│  • Kurikulum.docx  [👁 Preview] │  └────────────────┘  │
│                                  │                      │
│  CATATAN DOSEN:                  │  [SUBMIT KEPUTUSAN]  │
│  "PO telah ditetapkan oleh..."   │                      │
│                                  │  ─────────────────── │
│  RIWAYAT SEBELUMNYA:            │  Validasi sebelumnya: │
│  30 Mar — Revisi                 │  30 Mar: REVISI      │
│  "Upload ulang dokumen"          │                      │
└──────────────────────────────────┴──────────────────────┘
```

---

### 13.15 Wireframe — Riwayat Validasi

```
┌─────────────────────────────────────────────────────────┐
│  [← Dashboard]   Riwayat Validasi Saya                 │
├─────────────────────────────────────────────────────────┤
│  Filter: Status: [Semua ▾]   Prodi: [Semua ▾]          │
│          Tanggal: [01/04/2026] s/d [05/04/2026]         │
├─────────────────────────────────────────────────────────┤
│  ┌──────────────────────────────────────────────────┐  │
│  │ Prodi    │ Sub-kriteria  │ Tgl Valid.  │ Status  │  │
│  ├──────────────────────────────────────────────────┤  │
│  │ Tek.Info │ 1.1 PO Align.│ 3 Apr 10:00 │✅ Setuju│  │
│  │          │               │             │[Detail] │  │
│  ├──────────────────────────────────────────────────┤  │
│  │ Tek.Sipil│ 3.1 Teaching  │ 2 Apr 15:30 │🔄 Revisi│  │
│  │          │               │             │[Detail] │  │
│  ├──────────────────────────────────────────────────┤  │
│  │ Tek.Mesin│ 4.1 Assess.   │ 1 Apr 17:00 │✅ Setuju│  │
│  │          │               │             │[Detail] │  │
│  └──────────────────────────────────────────────────┘  │
│  Menampilkan 1–3 dari 12 riwayat                        │
└─────────────────────────────────────────────────────────┘
```

---

### 13.16 Diagram Alur Input-Output — Pengisian Submission

```
[DOSEN LOGIN]
     │
     ▼
[PILIH KARAKTERISTIK]
     │ INPUT  : klik sub-kriteria dari /dosen/prodi/{id}
     │ PROSES : load template items dari DB; buat submission draft jika belum ada
     │ OUTPUT : form pengisian tampil di /dosen/submission/{kriteria_id}
     ▼
[ISI CHECKLIST ITEM]
     │ INPUT  : centang/uncentang checkbox
     │ PROSES : PATCH /dosen/submission-item/{id} → SkorService::hitung()
     │          UPDATE submission SET skor = hasil
     │ OUTPUT : progress bar & skor real-time update via Alpine.js
     ▼
[UPLOAD DOKUMEN]
     │ INPUT  : pilih file (PDF/DOCX/XLSX, maks 10MB)
     │ PROSES : validasi → store → INSERT dokumen → recalculate skor
     │ OUTPUT : file tampil di daftar, skor naik
     │ ERROR  : format/ukuran salah → pesan error inline
     ▼
[SIMPAN DRAFT atau SUBMIT]
     │ DRAFT  : UPDATE status = 'draft', form tetap terbuka
     │ SUBMIT : cek skor ≥ threshold → UPDATE status = 'submitted'
     │          kirim notifikasi ke validator → form jadi read-only
     │ ERROR  : skor < threshold → tombol Submit disabled
     ▼
[VALIDATOR REVIEW]
     │ INPUT  : klik [Review] dari /validator/antrian
     │ PROSES : load submission + items + dokumen
     │ OUTPUT : halaman /validator/review/{submission_id}
     ▼
[KEPUTUSAN VALIDATOR]
     │ SETUJUI: UPDATE status = 'diterima', INSERT validasi, notif dosen
     │ REVISI : UPDATE status = 'revisi',   INSERT validasi, notif dosen
     │ TOLAK  : UPDATE status = 'ditolak',  INSERT validasi, notif dosen
     │          komentar wajib pada revisi & tolak
     ▼
[GENERATE LAPORAN]
     │ INPUT  : klik [Generate Laporan PDF Resmi] di /dosen/prodi/{id}/laporan
     │ PROSES : query submission 'diterima' → hitung grand total
     │          render Chart.js → generate PDF (async queue)
     │          INSERT laporan
     │ OUTPUT : PDF tersedia untuk download, tersimpan di histori
```

---

## 14. Rumus Perhitungan Skor

### 14.1 Definisi Skor

Skor adalah representasi persentase dari total bobot item template yang telah **terpenuhi** dalam sebuah submission, dibandingkan terhadap total bobot keseluruhan item dalam sub-karakteristik tersebut.

### 14.2 Formula Dasar — Skor per Submission

```
Skor_submission (%) = SUM(bobot_item × status_terpenuhi_item)

Dimana:
  status_terpenuhi_item = 1 jika item terpenuhi, 0 jika tidak
  SUM(bobot_item_total) selalu = 100 per sub-kriteria
```

**Contoh Perhitungan:**

| Item                   | Bobot    | Status | Kontribusi |
| ---------------------- | -------- | ------ | ---------- |
| PO1: Analisis masalah  | 20%      | ✅     | 20         |
| PO2: Design solusi     | 20%      | ✅     | 20         |
| PO3: Komunikasi teknis | 20%      | ✅     | 20         |
| PO4: Kerja tim         | 20%      | ✗      | 0          |
| PO5: Sosial & etika    | 20%      | ✗      | 0          |
| **Total**              | **100%** |        | **60%**    |

**Query SQL — `SkorService::hitung()`:**

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
            WHEN ti.tipe = 'narasi' THEN 0
            ELSE 0
        END
    ), 0) AS skor
FROM submission_item si
JOIN template_item ti ON ti.template_id = si.template_item_id
WHERE si.submission_id = :submission_id;
```

### 14.3 Formula Grand Total — Skor Keseluruhan Prodi

```
Skor_Total_Prodi (%) = SUM(Skor_i × Bobot_kriteria_i)

Dimana hanya submission berstatus 'diterima' yang diikutkan.
```

**Query SQL — `SkorService::hitungGrandTotal()`:**

```sql
SELECT
    ROUND(
        SUM(s.skor * k.bobot) / NULLIF(SUM(k.bobot), 0),
        2
    ) AS skor_total
FROM submission s
JOIN kriteria k ON k.kriteria_id = s.kriteria_id
WHERE s.prodi_id = :prodi_id
  AND s.status   = 'diterima'
  AND k.level    = 2;
```

### 14.4 Threshold dan Status Kesiapan

| Rentang Skor | Status          | Label UI            |
| ------------ | --------------- | ------------------- |
| ≥ 80%        | Sangat Siap     | ✅ FULLY COMPLIANT  |
| 75% – 79.99% | Siap            | ✅ COMPLIANT        |
| 60% – 74.99% | Hampir Siap     | ⚠️ ALMOST COMPLIANT |
| < 60%        | Perlu Perbaikan | ❌ NOT COMPLIANT    |

- **Threshold minimum IABEE = 75%** untuk status overall COMPLIANT
- Submission individu dengan skor < 50% tidak dapat disubmit
- Karakteristik dengan skor < 70% ditandai sebagai "area kritis" dalam laporan

### 14.5 Status Terpenuhi per Tipe Item

| Tipe Item   | Kondisi Terpenuhi                    | Kontribusi ke Skor |
| ----------- | ------------------------------------ | ------------------ |
| `checklist` | `nilai_checklist = TRUE`             | ✅ Ya              |
| `upload`    | Minimal 1 file dokumen terupload     | ✅ Ya              |
| `numerik`   | `nilai_numerik >= nilai_min_numerik` | ✅ Ya              |
| `narasi`    | Tidak dihitung — selalu bobot 0      | ❌ Tidak           |

---

## 15. Verifikasi & Test Cases

### 15.1 Test Case 1 — Submission 100% Lengkap

**Skenario:** 5 item checklist bobot 20% masing-masing, semua dicentang.

| Item     | Bobot | Status | Kontribusi       |
| -------- | ----- | ------ | ---------------- |
| Item 1–5 | 20%   | ✅     | 20 masing-masing |

```
Skor           : 100%
Status         : FULLY COMPLIANT
Tombol Submit  : ENABLED
Verifikasi     : 20+20+20+20+20 = 100 ✓
```

### 15.2 Test Case 2 — Submission Partial (60%)

**Skenario:** 5 item checklist bobot merata, hanya 3 dicentang.

```
Skor           : 60%
Status         : ALMOST COMPLIANT
Tombol Submit  : ENABLED (≥ 50%)
Verifikasi     : 20+20+20+0+0 = 60 ✓
```

### 15.3 Test Case 3 — Upload Dokumen

**Skenario:** Item tipe `upload`, dosen upload 1 file valid `RPS_2024.pdf` (2.3MB).

| Langkah                         | Expected             |
| ------------------------------- | -------------------- |
| Validasi format & ukuran        | ✅ Lolos             |
| File tersimpan di storage       | ✅ Ada               |
| Row terbuat di tabel `dokumen`  | ✅ Ada               |
| `submission_item` status update | ✅ Terpenuhi         |
| Skor submission naik            | ✅ Sesuai bobot item |

### 15.4 Test Case 4 — Input Numerik

**Skenario:** `nilai_min_numerik = 50`.

| Sub-test | Input   | Expected                          |
| -------- | ------- | --------------------------------- |
| 4A       | `82.5`  | ✅ Terpenuhi                      |
| 4B       | `45.0`  | ✗ Tidak terpenuhi                 |
| 4C       | `"abc"` | ❌ Error: bukan angka             |
| 4D       | `50.0`  | ✅ Terpenuhi (boundary inclusive) |

### 15.5 Test Case 5 — Grand Total Skor Prodi

**Skenario:** 7 karakteristik, semua `diterima`.

| Karakteristik | Skor | Bobot | Tertimbang |
| ------------- | ---- | ----- | ---------- |
| PO            | 60%  | 15%   | 9.00       |
| Curriculum    | 65%  | 20%   | 13.00      |
| Teaching      | 80%  | 15%   | 12.00      |
| Assessment    | 70%  | 15%   | 10.50      |
| Facilities    | 72%  | 10%   | 7.20       |
| Research      | 64%  | 10%   | 6.40       |
| Alumni        | 68%  | 15%   | 10.20      |

```
Grand Total = 9+13+12+10.5+7.2+6.4+10.2 = 68.30%
Gap ke standar = 75.00 - 68.30 = 6.70 poin
Status = ALMOST COMPLIANT
```

### 15.6 Test Case 6 — Konsistensi Skor (Re-verification)

**Skenario:** Skor tersimpan di `submission.skor` harus identik dengan recalculate ulang.

```php
$stored       = $submission->skor;           // dari DB
$recalculated = $skorService->hitung($id);   // hitung ulang
assert(abs($stored - $recalculated) < 0.01); // toleransi 0.01
```

### 15.7 Testing Checklist

**Unit Tests:**

- [ ] `SkorService::hitung()` benar untuk semua tipe item
- [ ] `SkorService::hitungGrandTotal()` weighted average benar
- [ ] Validasi numerik menolak nilai < `nilai_min_numerik`
- [ ] Validasi format & ukuran file upload
- [ ] State machine status submission

**Integration Tests:**

- [ ] Alur lengkap: submit → review → skor laporan benar
- [ ] Upload file → skor naik sesuai bobot
- [ ] Generate PDF hanya ambil submission `diterima`

**E2E Tests:**

- [ ] Progress bar update real-time tanpa reload
- [ ] Tombol Submit disabled jika skor < 50%
- [ ] Preview dokumen buka di browser
- [ ] Validator antrian hanya tampilkan `submitted`
- [ ] Gap analysis tampil benar di laporan

---

## 16. Validasi Input & Edge Cases

### 16.1 Aturan Validasi per Tipe Item

| Tipe        | Aturan                                               | Pesan Error                                      |
| ----------- | ---------------------------------------------------- | ------------------------------------------------ |
| `checklist` | Boolean (true/false)                                 | —                                                |
| `upload`    | Format: PDF, DOCX, XLSX; Maks 10MB; Maks 5 file/item | "Format tidak didukung" / "Ukuran melebihi 10MB" |
| `numerik`   | Angka; ≥ `nilai_min_numerik` jika diset              | "Input harus angka" / "Nilai minimal adalah X"   |
| `narasi`    | Teks bebas; maks 5000 karakter                       | "Teks terlalu panjang"                           |

**Aturan Konfigurasi Template (Admin):**

- Total bobot item wajib = 100. Sistem blokir simpan jika tidak terpenuhi.
- Item `narasi` wajib bobot = 0.
- Minimal 1 item wajib (`wajib = TRUE`) per sub-kriteria.

### 16.2 Penanganan Edge Cases

**A — Upload File Duplikat:** Simpan keduanya dengan suffix timestamp. Tidak ada auto-replace.

**B — Submission Locked setelah `diterima`:** Dosen tidak bisa edit. Admin bisa buka kunci via panel admin dengan audit log.

**C — Laporan dengan Submission Belum Selesai:** PDF tetap bisa di-generate, disertai catatan "N item belum final". Skor hanya dari yang `diterima`.

**D — Revisi Berulang:** Antrian validator selalu menampilkan versi submission terbaru. Riwayat semua validasi tersimpan di tabel `validasi`.

**E — Concurrent Edit:** Database-level locking. Request yang belakangan mendapat `409 Conflict`. Dosen diminta reload.

**F — File Hilang dari Storage:** Preview tampilkan "File tidak ditemukan". Admin bisa jalankan `php artisan storage:audit` untuk bersihkan referensi orphan.

---

## 17. Kriteria Penerimaan

- [ ] Halaman login berfungsi dan redirect ke dashboard sesuai role
- [ ] Admin dapat menambah, edit, hapus user dengan role yang benar
- [ ] Admin dapat menambah, edit, hapus program studi
- [ ] Admin dapat assign dan melepas user (dosen/validator) ke prodi
- [ ] Admin dapat menambah karakteristik dan sub-karakteristik IABEE
- [ ] Admin dapat mengkonfigurasi template item per sub-karakteristik (bobot total harus 100)
- [ ] Admin dapat melihat ringkasan progress semua prodi di dashboard
- [ ] Dosen melihat hanya prodi yang di-assign ke dirinya
- [ ] Dosen dapat melihat daftar karakteristik beserta status dan skor progresnya
- [ ] Dosen dapat mengisi template dan menyimpan sebagai draft
- [ ] Dosen dapat mengupload file dokumen per item tipe `upload`
- [ ] Dosen dapat mengsubmit dan melihat hasil validasi + komentar validator
- [ ] Dosen dapat memperbaiki dan re-submit submission yang berstatus revisi
- [ ] Skor submission diperbarui secara real-time saat dosen mengisi item
- [ ] Tombol Submit disabled jika skor sementara < 50%
- [ ] Validator hanya melihat submission dari prodi yang ia di-assign
- [ ] Validator dapat melihat antrian submission dan melakukan review
- [ ] Validator dapat memfilter antrian berdasarkan prodi dan kriteria
- [ ] Validator wajib mengisi komentar saat memberi status revisi/tolak
- [ ] Validator dapat melihat riwayat validasi dengan filter status dan tanggal
- [ ] Laporan PDF dapat di-generate dan mencantumkan grafik radar
- [ ] Laporan hanya menghitung submission yang telah disetujui validator
- [ ] Gap analysis ditampilkan pada halaman laporan
- [ ] Skor grand total menggunakan formula weighted average berdasarkan bobot karakteristik
- [ ] Semua test case pada Bagian 15 lulus dengan hasil yang sesuai expected output

---

## 18. Pertanyaan Klarifikasi untuk Stakeholder

| No. | Pertanyaan                                                              | Opsi                           | Status |
| --- | ----------------------------------------------------------------------- | ------------------------------ | ------ |
| 1   | Berapa threshold minimum overall untuk status "COMPLIANT"?              | 75% / 80% / Custom             | ❓     |
| 2   | Apakah threshold per karakteristik sama semua atau berbeda?             | Sama semua / Per karakteristik | ❓     |
| 3   | Apakah bobot karakteristik fixed by system atau customizable per prodi? | Fixed / Custom per prodi       | ❓     |
| 4   | Berapa batas minimum skor untuk tombol Submit diaktifkan?               | 50% / 60% / Tidak ada          | ❓     |
| 5   | Upload dokumen langsung terpenuhi atau perlu validator approve dulu?    | Auto / Butuh validator         | ❓     |
| 6   | Jika submission di-`tolak`, apakah data item ter-reset atau tersimpan?  | Reset / Tetap ada              | ❓     |
| 7   | Apakah ada deadline pengisian per prodi atau per karakteristik?         | Ya / Tidak                     | ❓     |
| 8   | Berapa banyak karakteristik utama IABEE 2026 yang perlu didukung?       | N karakteristik                | ❓     |
| 9   | Apakah validator dapat di-assign ke lebih dari satu prodi?              | Ya / Tidak                     | ❓     |
| 10  | Apakah admin juga bisa menjadi validator atau harus role terpisah?      | Boleh rangkap / Harus terpisah | ❓     |

---

_Dokumen ini diperbarui ke versi 3.0.0. Halaman-halaman baru yang ditambahkan didokumentasikan lengkap di Bagian 10. Perbarui tanda ❓ pada Bagian 18 setelah konfirmasi stakeholder diperoleh._
