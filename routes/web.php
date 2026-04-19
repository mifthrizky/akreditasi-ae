<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\TemplateItemController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\ValidatorController;

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

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
});



// Dashboard admin
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin routes
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/assign-prodi', [UserController::class, 'assignProdi'])->name('users.assignProdi');
    Route::resource('program-studi', ProgramStudiController::class);
    Route::resource('kriteria', KriteriaController::class);
    Route::get('/kriteria/{kriteria}/template', [KriteriaController::class, 'showTemplate'])->name('kriteria.template');
    Route::resource('template-items', TemplateItemController::class);

    // Dosen routes
    Route::get('/dosen/prodi', [DosenController::class, 'indexProdi'])->name('dosen.prodi.index');
    Route::get('/dosen/prodi/{prodi_id}', [DosenController::class, 'showProdiKriteria'])->name('dosen.prodi.kriteria');
    Route::get('/dosen/submission/{prodi_id}/{kriteria_id}', [DosenController::class, 'showSubmission'])->name('dosen.submission.show');
    Route::post('/dosen/submission/{prodi_id}/{kriteria_id}', [DosenController::class, 'storeSubmission'])->name('dosen.submission.store');
    Route::post('/dosen/submission/{prodi_id}/{submission_id}/reset', [DosenController::class, 'resetSubmission'])->name('dosen.submission.reset');
    Route::get('/dosen/submission/{submission_id}/review', [DosenController::class, 'showReview'])->name('dosen.submission.review');
    Route::get('/dosen/prodi/{prodi_id}/laporan', [DosenController::class, 'showProdiLaporan'])->name('dosen.prodi.laporan');
    Route::post('/dosen/prodi/{prodi_id}/laporan', [DosenController::class, 'storeLaporan'])->name('dosen.prodi.laporan.store');

    // Validator routes
    Route::get('/validator/antrian', [ValidatorController::class, 'indexAntrian'])->name('validator.antrian');
    Route::get('/validator/antrian/{submission_id}', [ValidatorController::class, 'showReview'])->name('validator.review');
    Route::post('/validator/antrian/{submission_id}/validasi', [ValidatorController::class, 'storeValidasi'])->name('validator.validasi.store');
});
