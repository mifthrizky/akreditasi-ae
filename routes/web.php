<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProgramStudiController;
use App\Http\Controllers\Admin\KriteriaController;
use App\Http\Controllers\Admin\TemplateItemController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\SubmissionController;
use App\Http\Controllers\Dosen\LaporanController;
use App\Http\Controllers\Dosen\RiwayatController as DosenRiwayatController;
use App\Http\Controllers\Validator\DashboardController as ValidatorDashboardController;
use App\Http\Controllers\Validator\ValidasiController;
use App\Http\Controllers\Validator\RiwayatController as ValidatorRiwayatController;

// Favicon route
Route::get('/favicon.ico', function () {
    $favPath = public_path('images/favicon.ico');
    if (file_exists($favPath)) {
        return response()->file($favPath, [
            'Content-Type' => 'image/x-icon',
            'Cache-Control' => 'public, max-age=31536000'
        ]);
    }
    abort(404);
});

Route::get('/', function () {
    return view('welcome');
});

// ============================================================
// AUTHENTICATION ROUTES (Guest)
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
});

// ============================================================
// AUTHENTICATED ROUTES (All roles)
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // ============================================================
    // ADMIN ROUTES
    // ============================================================
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Permission Management (Kelola Izin)
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::post('/permissions/{routeName}', [PermissionController::class, 'toggleRole'])->name('permissions.toggle');
        Route::delete('/permissions/reset', [PermissionController::class, 'reset'])->name('permissions.reset');

        // User Management
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/assign-prodi', [UserController::class, 'assignProdi'])->name('users.assignProdi');

        // Program Studi Management
        Route::resource('program-studi', ProgramStudiController::class);

        // Kriteria Management
        Route::resource('kriteria', KriteriaController::class);
        Route::get('/kriteria/{kriteria}/template', [KriteriaController::class, 'showTemplate'])->name('kriteria.template');

        // Template Item Management
        Route::resource('template-items', TemplateItemController::class);
    });

    // ============================================================
    // DOSEN ROUTES
    // ============================================================
    Route::prefix('dosen')->name('dosen.')->middleware('role:dosen')->group(function () {
        // Dosen Dashboard
        Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');

        // Prodi List & Kriteria List
        Route::get('/prodi', [SubmissionController::class, 'indexProdi'])->name('prodi.index');
        Route::get('/prodi/{prodi_id}', [SubmissionController::class, 'kriteriIndex'])->name('submission.kriteria-index');

        // Submission Management
        Route::get('/submission/{prodi_id}/{kriteria_id}', [SubmissionController::class, 'show'])
            ->name('submission.show')
            ->where(['prodi_id' => '[0-9]+', 'kriteria_id' => '[0-9]+']);
        Route::post('/submission/{prodi_id}/{kriteria_id}', [SubmissionController::class, 'store'])
            ->name('submission.store')
            ->where(['prodi_id' => '[0-9]+', 'kriteria_id' => '[0-9]+']);
        Route::post('/submission/{submission_id}/reset', [SubmissionController::class, 'reset'])->name('submission.reset');
        Route::get('/submission/{submission_id}/review', [SubmissionController::class, 'review'])->name('submission.review');

        // Submission Riwayat (Audit Log)
        Route::get('/submission/{submission_id}/riwayat', [DosenRiwayatController::class, 'show'])->name('submission.riwayat');

        // Laporan (Report)
        Route::get('/prodi/{prodi_id}/laporan', [LaporanController::class, 'show'])->name('laporan.show');
        Route::post('/prodi/{prodi_id}/laporan', [LaporanController::class, 'store'])->name('laporan.store');
    });

    // ============================================================
    // VALIDATOR ROUTES
    // ============================================================
    Route::prefix('validator')->name('validator.')->middleware('role:validator')->group(function () {
        // Validator Dashboard
        Route::get('/dashboard', [ValidatorDashboardController::class, 'index'])->name('dashboard');

        // Antrian Review (Queue)
        Route::get('/antrian', [ValidasiController::class, 'indexAntrian'])->name('antrian.index');
        Route::get('/antrian/{submission_id}', [ValidasiController::class, 'show'])->name('antrian.show');
        Route::post('/antrian/{submission_id}/validasi', [ValidasiController::class, 'store'])->name('validasi.store');

        // Riwayat Validasi (Validation History)
        Route::get('/riwayat', [ValidatorRiwayatController::class, 'index'])->name('riwayat.index');
        Route::get('/riwayat/{auditLogId}', [ValidatorRiwayatController::class, 'show'])->name('riwayat.show');
    });
});
