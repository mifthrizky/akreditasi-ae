# Product Requirements Document (PRD)

## Sistem Pedoman Kurikulum Akreditasi IABEE 2026

**Versi:** 1.0.0
**Tanggal:** April 2026
**Status:** Draft

---

## 1. Latar Belakang

Proses persiapan akreditasi internasional IABEE (_Institution of Accreditation Board for Engineering Education_) memerlukan kelengkapan dokumen kurikulum yang terstruktur dan terverifikasi. Saat ini proses pengecekan kesiapan dokumen masih dilakukan secara manual sehingga sulit dipantau kemajuannya secara real-time.

Sistem ini dibangun untuk membantu satu jurusan dengan beberapa program studi dalam mempersiapkan kelengkapan dokumen kurikulum sesuai karakteristik IABEE 2026, mulai dari pengisian template, validasi dokumen, hingga generate laporan kesiapan akreditasi.

---

## 2. Tujuan Produk

- Menyediakan platform terpusat untuk pengelolaan dokumen kurikulum per program studi
- Memastikan setiap karakteristik IABEE 2026 terdokumentasi dan terverifikasi dengan benar
- Menghasilkan laporan kesiapan akreditasi otomatis dalam format PDF beserta grafik visualisasi
- Mengurangi risiko dokumen yang diisi sembarangan melalui proses validasi oleh validator

---

## 3. Ruang Lingkup

### Masuk dalam scope

- Manajemen karakteristik dan sub-karakteristik kurikulum IABEE 2026
- Template per karakteristik (checklist, upload dokumen, input numerik, narasi)
- Alur submission dokumen oleh dosen dan validasi oleh validator
- Laporan kesiapan kurikulum per program studi (PDF + grafik)
- Manajemen user dengan tiga role: admin, dosen, validator

### Tidak masuk dalam scope (saat ini)

- RPS / silabus per mata kuliah
- Karakteristik akreditasi non-kurikulum (fasilitas, penelitian, dll.)
- Integrasi dengan sistem kampus (SIAKAD, e-learning)
- Penilaian langsung dari asesor IABEE eksternal

---

## 4. Pengguna dan Role

| Role                  | Deskripsi                                  | Akses Utama                                         |
| --------------------- | ------------------------------------------ | --------------------------------------------------- |
| **Admin**             | Mengelola seluruh data master sistem       | CRUD prodi, user, karakteristik, template           |
| **Dosen / Tim Prodi** | Mengisi dan mengsubmit dokumen kurikulum   | Dashboard prodi, isi template, submit, lihat status |
| **Validator**         | Memeriksa kesesuaian dokumen yang diupload | Antrian review, approve / kembalikan + catatan      |

---

## 5. Alur Sistem

### 5.1 Alur umum

```
Admin setup → Dosen isi template → Submit → Validator review → Approved → Generate laporan
```

### 5.2 Status submission

```
draft → submitted → [diterima | revisi | ditolak]
```

- **draft**: Dosen masih mengisi, belum disubmit
- **submitted**: Sudah disubmit, menunggu review validator
- **diterima**: Validator menyetujui dokumen
- **revisi**: Dikembalikan ke dosen dengan catatan, perlu perbaikan
- **ditolak**: Dokumen tidak relevan, perlu pengisian ulang

### 5.3 Dua lapis laporan

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

### 6.2 Dosen

- Dashboard program studi: daftar karakteristik + status tiap karakteristik
- Halaman detail karakteristik: panduan + template pengisian
- Isi template: checklist, upload dokumen (PDF/DOCX/XLSX), input numerik, narasi
- Simpan sebagai draft atau submit untuk validasi
- Melihat catatan dari validator dan melakukan revisi

### 6.3 Validator

- Dashboard antrian submission yang menunggu review
- Preview dokumen yang diupload langsung di browser
- Memberi status: disetujui / revisi / ditolak + komentar wajib saat revisi/tolak
- Riwayat validasi yang pernah dilakukan

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

### 8.1 Skema lengkap

```sql
-- ============================================================
-- DATABASE SISTEM PEDOMAN KURIKULUM AKREDITASI IABEE 2026
-- Tech stack: Laravel 11 + PostgreSQL 16
-- ============================================================

-- Hapus tabel jika sudah ada (urutan: child dulu baru parent)
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
-- Catatan: nama tabel "users" (bukan "user") karena
-- "user" adalah reserved keyword di PostgreSQL
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
    FOREIGN KEY (user_id)  REFERENCES users(user_id)               ON DELETE CASCADE,
    FOREIGN KEY (prodi_id) REFERENCES program_studi(prodi_id)      ON DELETE CASCADE,
    UNIQUE (user_id, prodi_id)
);

-- ------------------------------------------------------------
-- TABEL: kriteria
-- Self-referencing untuk hierarki kriteria → sub-kriteria
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
-- Item-item yang harus diisi dosen per sub-kriteria
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
-- Unique constraint: satu prodi hanya boleh punya 1 submission
-- aktif per sub-kriteria
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

-- Trigger untuk auto-update kolom updated_at di PostgreSQL
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
-- Kolom nilai dipilih sesuai tipe template_item-nya
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
-- Satu submission_item bisa punya lebih dari satu file
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
-- One-to-one dengan submission (satu submission, satu validasi aktif)
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
-- Satu prodi bisa generate laporan berkali-kali
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

### 8.2 Ringkasan tabel dan relasi

| Tabel             | Deskripsi                                      | Relasi utama                                                           |
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

### 8.3 Catatan penting perbedaan MySQL → PostgreSQL

| MySQL                                | PostgreSQL                         | Keterangan                                         |
| ------------------------------------ | ---------------------------------- | -------------------------------------------------- |
| `INT AUTO_INCREMENT`                 | `SERIAL`                           | Auto-increment di PostgreSQL                       |
| `ENUM('a','b')`                      | `VARCHAR CHECK (col IN ('a','b'))` | PostgreSQL support ENUM tapi CHECK lebih fleksibel |
| `ON UPDATE CURRENT_TIMESTAMP`        | Trigger `update_updated_at()`      | Harus menggunakan trigger di PostgreSQL            |
| Nama tabel `user`                    | `users`                            | `user` adalah reserved keyword di PostgreSQL       |
| `ON UPDATE CASCADE` pada self-ref FK | Didukung penuh                     | Tidak ada perbedaan                                |

---

## 9. Struktur Folder Laravel (Rencana)

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

## 10. API Endpoint (Rencana)

| Method | Endpoint                              | Role        | Deskripsi                           |
| ------ | ------------------------------------- | ----------- | ----------------------------------- |
| GET    | `/admin/kriteria`                     | admin       | Daftar semua kriteria               |
| POST   | `/admin/kriteria`                     | admin       | Tambah kriteria baru                |
| POST   | `/admin/kriteria/{id}/template`       | admin       | Tambah item template                |
| GET    | `/dosen/prodi/{id}/kriteria`          | dosen       | Daftar kriteria + status per prodi  |
| GET    | `/dosen/submission/{id}`              | dosen       | Detail submission dan item-nya      |
| POST   | `/dosen/submission`                   | dosen       | Buat atau update submission (draft) |
| POST   | `/dosen/submission/{id}/submit`       | dosen       | Submit untuk validasi               |
| POST   | `/dosen/submission-item/{id}/dokumen` | dosen       | Upload dokumen                      |
| GET    | `/validator/antrian`                  | validator   | Daftar submission menunggu review   |
| POST   | `/validator/validasi/{submission_id}` | validator   | Setujui / kembalikan submission     |
| GET    | `/laporan/{prodi_id}`                 | semua       | Lihat laporan progres prodi         |
| POST   | `/laporan/{prodi_id}/generate`        | dosen/admin | Generate laporan PDF resmi          |

---

## 11. Aturan Bisnis Penting

1. **Template hanya untuk sub-kriteria** — `template_item` hanya boleh berelasi ke `kriteria` dengan `level = 1`. Kriteria utama (`level = 0`) hanya sebagai pengelompok.
2. **Satu submission per sub-kriteria per prodi** — Dijaga oleh `UNIQUE (prodi_id, kriteria_id)` di tabel `submission`.
3. **Skor dihitung otomatis** — Saat dosen mengisi `submission_item`, sistem menghitung skor dari bobot item yang terpenuhi. Skor disimpan di kolom `skor` tabel `submission`.
4. **Laporan resmi hanya dari submission `diterima`** — Saat generate laporan, hanya submission dengan status `diterima` yang dihitung ke skor total.
5. **Validator wajib isi komentar saat revisi/tolak** — Validasi pada status `revisi` atau `ditolak` wajib mengisi kolom `komentar`.
6. **Kedalaman hierarki maksimal 2 level** — Level 0 (kriteria utama) dan level 1 (sub-kriteria). Tidak ada sub-sub-kriteria.

---

## 12. Kriteria Penerimaan (Acceptance Criteria)

- [ ] Admin dapat menambah karakteristik dan sub-karakteristik IABEE
- [ ] Admin dapat mengkonfigurasi template item per sub-karakteristik
- [ ] Dosen dapat melihat daftar karakteristik beserta status progresnya
- [ ] Dosen dapat mengisi template dan menyimpan sebagai draft
- [ ] Dosen dapat mengupload file dokumen per item tipe `upload`
- [ ] Dosen dapat mengsubmit dan melihat hasil validasi + komentar validator
- [ ] Validator dapat melihat antrian submission dan melakukan review
- [ ] Sistem menghitung skor otomatis saat pengisian submission
- [ ] Laporan PDF dapat di-generate dan mencantumkan grafik radar
- [ ] Laporan hanya menghitung submission yang telah disetujui validator

---

_Dokumen ini akan diperbarui seiring perkembangan proyek._
