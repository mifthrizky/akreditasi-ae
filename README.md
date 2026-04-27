# 📚 Sistem Pemeriksa Pedoman Kurikulum Persiapan Akreditasi
---

<div align="center">

[![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-4.2-06B6D4?logo=tailwindcss)](https://tailwindcss.com)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-Latest-336791?logo=postgresql)](https://www.postgresql.org)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

</div>

---

## 📋 Daftar Isi

- [Pendahuluan](#pendahuluan)
- [Fitur Utama](#fitur-utama)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Prasyarat Instalasi](#prasyarat-instalasi)
- [Setup & Instalasi](#setup--instalasi)
- [Struktur Proyek](#struktur-proyek)
- [Panduan Penggunaan](#panduan-penggunaan)
- [Arsitektur Sistem](#arsitektur-sistem)
- [Modul & Fitur Lengkap](#modul--fitur-lengkap)
- [API Endpoint](#api-endpoint)
- [Database Schema](#database-schema)
- [Kontribusi](#kontribusi)
- [Lisensi](#lisensi)

---

## 🎯 Pendahuluan

Sistem Pedoman Kurikulum Akreditasi IABEE 2026 adalah platform terpusat yang dirancang untuk membantu institusi pendidikan dalam mempersiapkan akreditasi internasional IABEE (_Institution of Accreditation Board for Engineering Education_).

Sistem ini memfasilitasi:

- ✅ Pengelolaan dokumen kurikulum terstruktur
- ✅ Pengisian template per karakteristik akreditasi
- ✅ Validasi dokumen oleh tim validator
- ✅ Kalkulasi skor otomatis berbasis bobot
- ✅ Laporan kesiapan akreditasi dalam format PDF
- ✅ Visualisasi progress melalui grafik radar

### 📌 Latar Belakang Masalah

| Masalah Sebelumnya              | Solusi dalam Sistem                         |
| ------------------------------- | ------------------------------------------- |
| Pengecekan manual, rawan error  | Kalkulasi skor otomatis berbasis bobot      |
| Tidak ada visibilitas real-time | Dashboard dengan progress per karakteristik |
| Dokumen tersebar                | Satu platform terpusat per prodi            |
| Tidak ada feedback terstruktur  | Sistem komentar validator per submission    |
| Sulit generate laporan          | Generate laporan PDF + grafik otomatis      |

---

## ✨ Fitur Utama

### 🔐 Manajemen User & Autentikasi

- **3 Role Sistem:** Admin, Dosen, Validator
- **Dashboard Role-Based:** Akses sesuai peran pengguna
- **Manajemen User:** CRUD user dengan assignment prodi

### 📊 Manajemen Kriteria & Template

- **Hierarki 4-Level:** Kriteria → Sub-grup → Sub-kriteria → Template item
- **Template Dinamis:** Checklist, upload dokumen, input numerik, narasi
- **Master Data:** Kelola kriteria, bobot, deskripsi

### 📝 Submission & Validasi Dokumen

- **Form Submission:** Isi template dokumen kurikulum
- **Antrian Review:** Validator memeriksa submission yang pending
- **Feedback Terstruktur:** Komentar validator per submission
- **Workflow Status:** Draft → Submitted → Approved/Revision/Rejected

### 📈 Analisis & Laporan

- **Kalkulasi Skor Real-Time:** Perhitungan otomatis skor submission
- **Laporan Kesiapan:** Generate PDF report per prodi
- **Grafik Radar:** Visualisasi perbandingan skor vs target IABEE
- **Gap Analysis:** Identifikasi area yang perlu perbaikan

### 🔍 Audit & Riwayat

- **Audit Log:** Pencatatan setiap aksi penting di sistem
- **Riwayat Validasi:** Tracking lengkap validasi submission
- **Filter & Search:** Cari riwayat berdasarkan kriteria

---

## 🛠️ Teknologi yang Digunakan

### Backend

| Komponen       | Teknologi       | Versi  |
| -------------- | --------------- | ------ |
| Framework      | Laravel         | 13.2.0 |
| Bahasa         | PHP             | 8.3.16 |
| Database       | PostgreSQL      | Latest |
| PDF Generation | DomPDF          | 3.1    |
| Authentication | Laravel Sanctum | Latest |

### Frontend

| Komponen      | Teknologi    | Versi  |
| ------------- | ------------ | ------ |
| CSS Framework | Tailwind CSS | 4.2.2  |
| Build Tool    | Vite         | 8.0.0  |
| HTTP Client   | Axios        | 1.11.0 |
| JavaScript    | Vanilla JS   | ES6+   |

### Development Tools

| Alat         | Fungsi                        |
| ------------ | ----------------------------- |
| Laravel Pint | Code formatting & linting     |
| PHPUnit      | Unit testing                  |
| Faker        | Database seeding              |
| Composer     | PHP dependency manager        |
| npm          | JavaScript dependency manager |

---

## 📦 Prasyarat Instalasi

### System Requirements

```
- OS: Windows 10+, macOS, atau Linux
- PHP: 8.3 atau lebih tinggi
- Composer: 2.0 atau lebih tinggi
- Node.js: 18 atau lebih tinggi
- npm: 9 atau lebih tinggi
- PostgreSQL: 12 atau lebih tinggi
```

### Verifikasi Instalasi

```bash
# Cek versi PHP
php --version

# Cek Composer
composer --version

# Cek Node.js
node --version
npm --version

# Cek PostgreSQL
psql --version
```

---

## ⚙️ Setup & Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/your-repo/acreditasi-ae.git
cd acreditasi-ae
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### 3. Setup Environment

```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=acreditasi_ae
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

Buat database PostgreSQL:

```bash
# Masuk ke PostgreSQL console
psql -U postgres

# Buat database
CREATE DATABASE acreditasi_ae;

# Exit
\q
```

### 5. Migrasi & Seeding Database

```bash
# Jalankan migrasi
php artisan migrate

# Seed data master (kriteria, template, user default)
php artisan db:seed
```

### 6. Build Frontend Assets

```bash
# Production build
npm run build

# Development with hot reload
npm run dev
```

### 7. Jalankan Aplikasi

```bash
# Development server
php artisan serve

# Akses aplikasi
# http://localhost:8000
```

### Setup Otomatis (Alternatif)

Gunakan script setup yang sudah disediakan:

```bash
composer run-script setup
```

---

## 📂 Struktur Proyek

```
acreditasi-ae/
├── app/
│   ├── Console/
│   │   └── Commands/          # Custom artisan commands
│   ├── Http/
│   │   ├── Controllers/       # Controller logika bisnis
│   │   │   ├── Admin/        # Admin controllers
│   │   │   ├── Dosen/        # Dosen controllers
│   │   │   ├── Validator/    # Validator controllers
│   │   │   └── Auth/         # Authentication
│   │   └── Middleware/       # Middleware (role, auth)
│   ├── Models/               # Eloquent models
│   │   ├── User.php
│   │   ├── ProgramStudi.php
│   │   ├── Kriteria.php
│   │   ├── Submission.php
│   │   ├── SubmissionItem.php
│   │   ├── TemplateItem.php
│   │   ├── Validasi.php
│   │   ├── AuditLog.php
│   │   └── Laporan.php
│   ├── Services/            # Business logic services
│   │   ├── SkorService.php
│   │   ├── GapAnalysisService.php
│   │   ├── RadarChartService.php
│   │   ├── LaporanService.php
│   │   └── AuditLogService.php
│   └── Providers/           # Service providers
├── bootstrap/               # Framework bootstrap files
├── config/                  # Configuration files
│   ├── app.php
│   ├── database.php
│   ├── auth.php
│   ├── navigation.php      # Route navigation config
│   └── services.php
├── database/
│   ├── migrations/         # Database migrations
│   ├── seeders/           # Database seeders
│   └── factories/         # Model factories for testing
├── public/
│   ├── index.php          # Entry point
│   └── storage/           # Public storage (uploads)
├── resources/
│   ├── css/               # Stylesheets
│   │   └── app.css
│   ├── js/                # JavaScript files
│   │   └── app.js
│   └── views/             # Blade templates
│       ├── layouts/       # Layout master
│       ├── admin/         # Admin pages
│       ├── dosen/         # Dosen pages
│       ├── validator/     # Validator pages
│       └── auth/          # Auth pages
├── routes/
│   ├── web.php            # Web routes
│   ├── api.php            # API routes
│   └── console.php        # Console routes
├── storage/               # Logs, cache, sessions
├── tests/                 # Unit & feature tests
├── vendor/                # Composer dependencies (auto-generated)
├── node_modules/          # npm dependencies (auto-generated)
├── .env.example           # Environment template
├── artisan                # CLI runner
├── composer.json          # PHP dependencies
├── package.json           # JavaScript dependencies
├── vite.config.js         # Vite configuration
├── phpunit.xml            # PHPUnit configuration
└── README.md              # Project overview
```

---

## 👥 Panduan Penggunaan

### 📌 Untuk Admin

#### 1. Dashboard Admin

- Akses: `http://localhost:8000/dashboard`
- Menu utama: Program Studi, Kriteria, User, Permissions

#### 2. Manajemen Program Studi

```bash
Menu: Admin → Program Studi
```

- **Tambah Prodi:** Klik tombol "Tambah Program Studi"
- **Edit Prodi:** Klik tombol edit di setiap baris
- **Hapus Prodi:** Klik tombol hapus dengan konfirmasi
- **Field:** Kode, Nama, Deskripsi

#### 3. Manajemen Kriteria

```bash
Menu: Admin → Kriteria
```

- **Struktur Hierarki:** Level 0 (Kriteria) → Level 1 (Sub-grup) → Level 2 (Sub-kriteria)
- **Tambah Level:** Klik "Tambah" di masing-masing level
- **Field:** Kode, Nama, Deskripsi, Bobot, Urutan

#### 4. Manajemen User

```bash
Menu: Admin → User
```

- **Tambah User:** Klik "Tambah User"
- **Assign Prodi:** Klik "Assign Prodi" untuk link user ke prodi
- **Edit User:** Klik "Edit" untuk ubah detail
- **Hapus User:** Klik "Hapus" untuk remove user

#### 5. Manajemen Template

```bash
Menu: Admin → Template Item
```

- **Tipe Template:** Checklist, Upload, Numerik, Narasi
- **Assign ke Kriteria:** Setiap sub-kriteria level 2 bisa punya banyak template items
- **Field:** Label, Tipe, Deskripsi, Urutan

### 📝 Untuk Dosen

#### 1. Dashboard Dosen

- Akses: `http://localhost:8000/dosen/dashboard`
- Tampilkan daftar prodi yang diassign

#### 2. Kelola Kriteria Prodi

```bash
Menu: Dosen → Kelola Prodi → Kriteria
```

- **Lihat Kriteria:** Hierarki lengkap kriteria per prodi
- **Status Submission:** Draft, Submitted, Accepted, Revision, Rejected
- **Summary:** Total sub-kriteria, status count

#### 3. Isi Submission

```bash
Menu: Dosen → Isi Submission
Route: /dosen/prodi/{id}/kriteria/{kriteria_id}
```

**Workflow:**

1. Klik tombol **"Isi"** pada sub-kriteria yang ingin diisi
2. **Isi form** sesuai tipe template:
    - ✅ **Checklist:** Centang untuk yes/no
    - 📄 **Upload:** Upload file dokumen (PDF, Word)
    - 🔢 **Numerik:** Masukkan angka dengan validasi
    - 📝 **Narasi:** Tulis deskripsi/penjelasan
3. **Simpan Draft** atau **Submit untuk Validasi**
4. Submit hanya bisa jika skor ≥ 50%

**Field Form:**

```
- Setiap input sesuai template item
- Validasi real-time
- Pesan error jika ada field required
- Progress bar menunjukkan kelengkapan
```

#### 4. Review Submission

```bash
Menu: Dosen → Review Submission
Route: /dosen/submission/{id}/review
```

- Lihat feedback dari validator
- Jika status "Revision" atau "Rejected": Edit dan submit ulang
- Lihat skor submission

### ✅ Untuk Validator

#### 1. Dashboard Validator

- Akses: `http://localhost:8000/validator/dashboard`
- Summary: Total pending, approved, revision, rejected

#### 2. Antrian Review

```bash
Menu: Validator → Antrian Review
```

- **Lihat Daftar:** Submission yang pending review
- **Filter:** By Program Studi, Kriteria, Status
- **Aksi:** Klik "Review" untuk buka submission

#### 3. Review Submission

```bash
Menu: Validator → Review → Show
Route: /validator/antrian/{id}
```

**Form Review:**

1. **Lihat Detail Submission**
    - Kriteria, Sub-kriteria, Dosen, Tanggal submit
    - Semua data yang di-upload dosen
2. **Pilih Keputusan:**
    - 🟢 **Approved (Diterima):** Submission lulus validasi
    - 🟡 **Revision (Revisi):** Diminta perbaikan
    - 🔴 **Rejected (Ditolak):** Tidak memenuhi standar

3. **Tambah Komentar:** Penjelasan keputusan untuk dosen

4. **Simpan:** Submit keputusan

#### 4. Riwayat Validasi

```bash
Menu: Validator → Riwayat Validasi
```

- **Filter:** By Prodi, Status, Tanggal range
- **Lihat Detail:** Klik "Lihat Detail" untuk reopen submission
- **Export:** (Optional) Download riwayat

---

## 🏗️ Arsitektur Sistem

### Architecture Pattern: MVC (Model-View-Controller)

```
┌─────────────────────────────────────────────────────────┐
│                    USER (Browser)                       │
└──────────────────────┬──────────────────────────────────┘
                       │ HTTP Request
                       ▼
┌─────────────────────────────────────────────────────────┐
│           ROUTING (routes/web.php)                      │
│  Mapping URL → Controller Action                        │
└──────────────────┬──────────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────────┐
│      MIDDLEWARE (Auth, Role, Permission)               │
│  - Check authentication                                 │
│  - Verify role-based access                            │
│  - Check page permissions                              │
└──────────────────┬──────────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────────┐
│    CONTROLLER (app/Http/Controllers/)                  │
│  - Handle request logic                                │
│  - Call services & models                              │
│  - Return view or response                             │
└──────┬──────────────────────────────────┬───────────────┘
       │                                  │
       ▼                                  ▼
┌──────────────────────┐    ┌────────────────────────────┐
│   SERVICE LAYER      │    │  ELOQUENT MODELS           │
│                      │    │  - User                    │
│ - SkorService        │    │  - ProgramStudi            │
│ - GapAnalysisService │    │  - Kriteria                │
│ - LaporanService     │    │  - Submission              │
│ - AuditLogService    │    │  - TemplateItem            │
│ - RadarChartService  │    │  - Validasi                │
└──────────┬───────────┘    │  - AuditLog                │
           │                │  - Laporan                 │
           │                └────────────┬────────────────┘
           │                             │
           └─────────────┬───────────────┘
                         │
                         ▼
         ┌───────────────────────────────┐
         │  DATABASE (PostgreSQL)        │
         │  - Tables                     │
         │  - Relationships              │
         │  - Queries                    │
         └───────────────────────────────┘
```

### Data Flow Submission

```
┌─────────────┐
│ Dosen Isi   │
│ Template    │
└──────┬──────┘
       │
       ▼
┌──────────────────┐
│ Submit Form Data │
│ (POST Request)   │
└──────┬───────────┘
       │
       ▼
┌──────────────────────────────────┐
│ SubmissionController::store()    │
│ - Validate input                 │
│ - Save SubmissionItem            │
│ - Update Submission status       │
└──────┬───────────────────────────┘
       │
       ▼
┌──────────────────────────────┐
│ SkorService::calculate()     │
│ - Hitung skor per item       │
│ - Aggregate per kriteria     │
│ - Return total score (%)     │
└──────┬───────────────────────┘
       │
       ▼
┌──────────────────────────────┐
│ Update Submission::skor      │
│ Store in database            │
└──────┬───────────────────────┘
       │
       ▼
┌──────────────────────────────┐
│ AuditLogService::logSubmit() │
│ Create audit log record      │
└──────┬───────────────────────┘
       │
       ▼
┌──────────────────────────┐
│ Redirect to Success Page │
└──────────────────────────┘
```

---

## 🎯 Modul & Fitur Lengkap

### 1️⃣ Modul Admin (app/Http/Controllers/Admin/)

#### **ProgramStudiController**

| Method      | Route                              | Fungsi             |
| ----------- | ---------------------------------- | ------------------ |
| `index()`   | GET /admin/program-studi           | Lihat daftar prodi |
| `create()`  | GET /admin/program-studi/create    | Form tambah prodi  |
| `store()`   | POST /admin/program-studi          | Simpan prodi baru  |
| `edit()`    | GET /admin/program-studi/{id}/edit | Form edit prodi    |
| `update()`  | PUT /admin/program-studi/{id}      | Update prodi       |
| `destroy()` | DELETE /admin/program-studi/{id}   | Hapus prodi        |

#### **KriteriaController**

| Method          | Route                       | Fungsi                  |
| --------------- | --------------------------- | ----------------------- |
| `index()`       | GET /admin/kriteria         | Lihat hierarki kriteria |
| `store()`       | POST /admin/kriteria        | Tambah kriteria level   |
| `update()`      | PUT /admin/kriteria/{id}    | Update kriteria         |
| `destroy()`     | DELETE /admin/kriteria/{id} | Hapus kriteria          |
| `updateOrder()` | POST /admin/kriteria/order  | Update urutan           |

#### **TemplateItemController**

| Method      | Route                            | Fungsi               |
| ----------- | -------------------------------- | -------------------- |
| `index()`   | GET /admin/template-item         | Lihat template items |
| `store()`   | POST /admin/template-item        | Tambah template      |
| `update()`  | PUT /admin/template-item/{id}    | Update template      |
| `destroy()` | DELETE /admin/template-item/{id} | Hapus template       |

#### **UserController**

| Method          | Route                               | Fungsi               |
| --------------- | ----------------------------------- | -------------------- |
| `index()`       | GET /admin/users                    | Lihat daftar user    |
| `store()`       | POST /admin/users                   | Tambah user          |
| `update()`      | PUT /admin/users/{id}               | Edit user            |
| `destroy()`     | DELETE /admin/users/{id}            | Hapus user           |
| `assignProdi()` | POST /admin/users/{id}/assign-prodi | Assign prodi ke user |

### 2️⃣ Modul Dosen (app/Http/Controllers/Dosen/)

#### **SubmissionController**

| Method           | Route                                         | Fungsi                    |
| ---------------- | --------------------------------------------- | ------------------------- |
| `indexProdi()`   | GET /dosen/prodi                              | Lihat prodi yang diassign |
| `kriteriIndex()` | GET /dosen/prodi/{id}/kriteria                | Lihat kriteria prodi      |
| `show()`         | GET /dosen/prodi/{id}/kriteria/{kriteria_id}  | Form isi submission       |
| `store()`        | POST /dosen/prodi/{id}/kriteria/{kriteria_id} | Simpan submission         |
| `review()`       | GET /dosen/submission/{id}/review             | Lihat review              |
| `reset()`        | POST /dosen/submission/{id}/reset             | Reset submission          |

### 3️⃣ Modul Validator (app/Http/Controllers/Validator/)

#### **SubmissionController** (di folder Validator)

| Method            | Route                        | Fungsi                    |
| ----------------- | ---------------------------- | ------------------------- |
| `index()`         | GET /validator/antrian       | Lihat antrian review      |
| `show()`          | GET /validator/antrian/{id}  | Form review submission    |
| `storeValidasi()` | POST /validator/antrian/{id} | Simpan keputusan validasi |

#### **DashboardController**

| Method       | Route                    | Fungsi              |
| ------------ | ------------------------ | ------------------- |
| `index()`    | GET /validator/dashboard | Dashboard validator |
| `getStats()` | GET /api/validator/stats | API stats           |

#### **RiwayatController**

| Method    | Route                       | Fungsi                 |
| --------- | --------------------------- | ---------------------- |
| `index()` | GET /validator/riwayat      | Lihat riwayat validasi |
| `show()`  | GET /validator/riwayat/{id} | Detail riwayat         |

### 4️⃣ Service Layer (app/Services/)

#### **SkorService**

```php
// Kalkulasi skor submission
calculate(Submission $submission): float

// Hitung untuk semua submission prodi
calculateAllForProdi(int $prodiId, string $status): array

// Total skor prodi
calculateTotalForProdi(int $prodiId, string $status): float
```

#### **GapAnalysisService**

```php
// Analisis gap dengan standar minimum IABEE
analyzeGaps(int $prodiId): array

// Identifikasi area yang perlu perbaikan
getGapAreas(int $prodiId): array
```

#### **RadarChartService**

```php
// Generate data untuk radar chart
generateChartData(int $prodiId): array

// Format untuk Chart.js
formatForChartJs(int $prodiId): string
```

#### **LaporanService**

```php
// Generate laporan PDF
generatePDF(int $prodiId): PDF

// Generate laporan data
generateReport(int $prodiId): array
```

#### **AuditLogService**

```php
// Log submission action
logSubmit(Submission $submission, float $score): void

// Log validasi action
logValidasi(Submission $submission, string $status): void

// Get audit trail
getAuditTrail(int $submissionId): array
```

---

## 🔌 API Endpoint

### Submission Endpoints

#### Get All Submissions (Validator)

```http
GET /api/validator/submissions?status=pending&prodi_id=1
Content-Type: application/json
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "data": [
        {
            "submission_id": 1,
            "prodi_id": 1,
            "kriteria_id": 5,
            "user_id": 2,
            "status": "submitted",
            "skor": 75.5,
            "submitted_at": "2026-04-20T10:30:00Z",
            "prodi": { "nama": "TRIN" },
            "kriteria": { "kode": "K1.1" },
            "user": { "nama": "Dr. Ahmad" }
        }
    ],
    "total": 15,
    "per_page": 10,
    "current_page": 1
}
```

#### Submit Keputusan Validasi

```http
POST /api/validator/submissions/{id}/validasi
Content-Type: application/json
Authorization: Bearer {token}

{
  "status": "approved",
  "komentar": "Dokumen sudah lengkap dan sesuai standar"
}
```

**Response (200):**

```json
{
    "message": "Validasi berhasil disimpan",
    "submission": {
        "submission_id": 1,
        "status": "diterima",
        "validasi": {
            "status": "approved",
            "komentar": "Dokumen sudah lengkap dan sesuai standar",
            "validated_at": "2026-04-20T14:30:00Z"
        }
    }
}
```

#### Get Submission Detail

```http
GET /api/submissions/{id}
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "submission": {
        "submission_id": 1,
        "prodi": { "prodi_id": 1, "nama": "TRIN", "kode": "TRIN" },
        "kriteria": {
            "kriteria_id": 5,
            "kode": "K1.1",
            "nama": "Penetapan PPM"
        },
        "user": {
            "user_id": 2,
            "nama": "Dr. Ahmad",
            "email": "ahmad@univ.ac.id"
        },
        "status": "submitted",
        "skor": 75.5,
        "submitted_at": "2026-04-20T10:30:00Z",
        "items": [
            {
                "submission_item_id": 1,
                "template_item_id": 1,
                "nilai_checklist": true,
                "nilai_teks": null,
                "nilai_numerik": null
            }
        ]
    }
}
```

### Laporan Endpoints

#### Generate PDF Laporan

```http
GET /api/laporan/{prodi_id}/pdf
Authorization: Bearer {token}
```

**Response:** PDF binary file

#### Get Laporan Data

```http
GET /api/laporan/{prodi_id}
Authorization: Bearer {token}
```

**Response (200):**

```json
{
    "laporan": {
        "prodi_id": 1,
        "prodi_nama": "TRIN",
        "total_skor": 78.5,
        "status_kesiapan": "Siap dengan perbaikan minor",
        "kriteria_scores": {
            "K1": 82.0,
            "K2": 76.5,
            "K3": 71.0,
            "K4": 78.0
        },
        "gap_analysis": {
            "areas_needing_improvement": ["K2.1", "K3.2"],
            "recommendation": "Fokus pada kurikulum dan asesmen"
        }
    }
}
```

---

## 🗄️ Database Schema

### Core Tables

#### Users Table

```sql
CREATE TABLE users (
  user_id SERIAL PRIMARY KEY,
  nama VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'dosen', 'validator') NOT NULL,
  email_verified_at TIMESTAMP,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### Program Studi Table

```sql
CREATE TABLE program_studi (
  prodi_id SERIAL PRIMARY KEY,
  kode VARCHAR(10) UNIQUE NOT NULL,
  nama VARCHAR(255) NOT NULL,
  deskripsi TEXT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### User Prodi Table (Many-to-Many)

```sql
CREATE TABLE user_prodi (
  user_id INT REFERENCES users(user_id),
  prodi_id INT REFERENCES program_studi(prodi_id),
  PRIMARY KEY (user_id, prodi_id)
);
```

#### Kriteria Table (Hierarchical)

```sql
CREATE TABLE kriteria (
  kriteria_id SERIAL PRIMARY KEY,
  parent_id INT REFERENCES kriteria(kriteria_id),
  kode VARCHAR(20) NOT NULL,
  nama VARCHAR(255) NOT NULL,
  deskripsi TEXT,
  level INT (0=Level utama, 1=Sub-grup, 2=Sub-kriteria),
  bobot DECIMAL(5, 2),
  urutan INT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### Template Item Table

```sql
CREATE TABLE template_item (
  template_id SERIAL PRIMARY KEY,
  kriteria_id INT REFERENCES kriteria(kriteria_id),
  label VARCHAR(255) NOT NULL,
  tipe ENUM('checklist', 'upload', 'numerik', 'narasi'),
  deskripsi TEXT,
  urutan INT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### Submission Table

```sql
CREATE TABLE submission (
  submission_id SERIAL PRIMARY KEY,
  prodi_id INT REFERENCES program_studi(prodi_id),
  kriteria_id INT REFERENCES kriteria(kriteria_id),
  user_id INT REFERENCES users(user_id),
  status VARCHAR(50) ('draft', 'submitted', 'diterima', 'revisi', 'ditolak'),
  skor DECIMAL(5, 2),
  submitted_at TIMESTAMP,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### Submission Item Table

```sql
CREATE TABLE submission_item (
  submission_item_id SERIAL PRIMARY KEY,
  submission_id INT REFERENCES submission(submission_id),
  template_item_id INT REFERENCES template_item(template_id),
  nilai_checklist BOOLEAN,
  nilai_teks TEXT,
  nilai_numerik DECIMAL(10, 2),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### Validasi Table

```sql
CREATE TABLE validasi (
  validasi_id SERIAL PRIMARY KEY,
  submission_id INT REFERENCES submission(submission_id),
  validator_id INT REFERENCES users(user_id),
  status VARCHAR(50) ('approved', 'revision', 'rejected'),
  komentar TEXT,
  validated_at TIMESTAMP,
  created_at TIMESTAMP
);
```

#### Audit Log Table

```sql
CREATE TABLE audit_log (
  id SERIAL PRIMARY KEY,
  submission_id INT REFERENCES submission(submission_id),
  user_id INT REFERENCES users(user_id),
  action VARCHAR(100),
  description TEXT,
  data_before JSONB,
  data_after JSONB,
  created_at TIMESTAMP
);
```

#### Page Permission Table

```sql
CREATE TABLE page_permission (
  id SERIAL PRIMARY KEY,
  route_name VARCHAR(255) UNIQUE,
  page_label VARCHAR(255),
  allowed_roles JSON,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Entity Relationship Diagram

```
Users (1) ──── (M) UserProdi (M) ──── (1) ProgramStudi
   │                                      │
   │                                      │
   └─────────────────┬────────────────────┘
                     │
                  (1:M)
                     │
                Submission ──── (1:M) SubmissionItem
                     │              (FK: template_id)
                     │
                  (1:M)
                     │
                Validasi ──── (1) Users (validator)
                     │
                  (1:M)
                     │
                AuditLog
```

---

## 🤝 Kontribusi

Kami menyambut kontribusi dari siapa saja untuk meningkatkan kualitas sistem ini!

### 📋 Proses Kontribusi

1. **Fork Repository**

    ```bash
    # Fork di GitHub
    # Clone fork anda
    git clone https://github.com/your-username/acreditasi-ae.git
    cd acreditasi-ae
    ```

2. **Buat Branch Feature**

    ```bash
    # Dari branch main/develop
    git checkout -b feature/nama-fitur
    # atau untuk bug fix
    git checkout -b bugfix/nama-bug
    ```

3. **Commit Changes**

    ```bash
    # Stage perubahan
    git add .

    # Commit dengan pesan deskriptif
    git commit -m "feat: tambah fitur X"
    # atau
    git commit -m "fix: perbaiki bug Y"
    ```

4. **Push ke Fork**

    ```bash
    git push origin feature/nama-fitur
    ```

5. **Buat Pull Request**
    - Buka GitHub
    - Klik "Compare & pull request"
    - Jelaskan perubahan secara detail
    - Submit pull request

### 📝 Commit Message Convention

Gunakan format ConventionalCommits:

```
type(scope): subject

body (optional)

footer (optional)
```

**Types:**

- `feat:` - Fitur baru
- `fix:` - Bug fix
- `docs:` - Documentation changes
- `style:` - Code style changes (formatting, semicolons, etc.)
- `refactor:` - Refactoring kode
- `perf:` - Performance improvements
- `test:` - Adding or updating tests
- `chore:` - Changes to build process, dependencies, etc.

**Contoh:**

```bash
git commit -m "feat(submission): tambah validasi checklist otomatis"
git commit -m "fix(laporan): perbaiki perhitungan skor bobot"
git commit -m "docs: update README dengan contoh API"
```

### 🧪 Testing

Sebelum submit PR, pastikan:

```bash
# Run unit tests
php artisan test

# Run style checking
./vendor/bin/pint

# Build frontend
npm run build
```

### 📋 Pull Request Template

```markdown
## Deskripsi Perubahan

Jelaskan perubahan apa yang anda lakukan dan mengapa.

## Type of Change

- [ ] New feature
- [ ] Bug fix
- [ ] Documentation update
- [ ] Refactoring

## Testing

Jelaskan bagaimana anda menguji perubahan ini.

## Checklist

- [ ] Kode sudah di-format dengan pint
- [ ] Tests telah ditambahkan/diupdate
- [ ] Documentation sudah diupdate
- [ ] Tidak ada breaking changes
```

### 🐛 Reporting Bugs

Jika menemukan bug, buat issue dengan:

1. **Deskripsi jelas** tentang apa yang salah
2. **Langkah reproduksi** untuk mengulang bug
3. **Ekspektasi vs Aktual behavior**
4. **Environment info** (PHP version, OS, browser, dll)

### 💡 Saran Fitur

Untuk mengusulkan fitur baru:

1. Jelaskan use case dan benefit
2. Contoh bagaimana fitur bekerja
3. Kemungkinan dampak pada sistem existing

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah **MIT License**.

```
MIT License

Copyright (c) 2026 Acreditasi IABEE Team

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

### Penggunaan

Anda bebas untuk:

- ✅ **Gunakan** proyek ini untuk keperluan non-komersial
- ✅ **Modifikasi** dan sesuaikan dengan kebutuhan anda
- ✅ **Distribusikan** versi modifikasi
- ✅ **Gunakan private** tanpa perlu membuka source code

Dengan syarat:

- 📝 Sertakan salinan lisensi dan pemberitahuan hak cipta
- ⚠️ Tidak ada jaminan atau tanggung jawab dari pembuat asli

---

## 📚 Referensi Tambahan

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [PostgreSQL Documentation](https://www.postgresql.org/docs)
- [IABEE Accreditation Standards](https://iabee.or.id/home)
- [Project PRD (prd.md)](./prd.md)
- [Panduan Submission (PANDUAN_SUBMISSION_TRIN.md)](./PANDUAN_SUBMISSION_TRIN.md)

---

<div align="center">

### Terima kasih telah berkontribusi! 🙏

**Dibuat dengan ❤️ untuk meningkatkan kualitas akreditasi pendidikan**

Versi 3.0.0 | April 2026

</div>
