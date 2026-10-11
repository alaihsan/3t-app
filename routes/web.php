<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemorizationController;
use App\Http\Controllers\QuranController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - 3T App (Takhassus, Tahsin, dan Tahfizh)
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Share Routes (Mode Read-Only untuk Orang Tua / Wali)
Route::get('/share/m/{share_token}', [ShareController::class, 'view'])->name('share.view');
Route::post('/share/m/{share_token}/verify-pin', [ShareController::class, 'verifyPin'])->name('share.verify-pin');
Route::get('/share/m/{share_token}/quran', [ShareController::class, 'quranIndex'])->name('share.quran-index');
Route::get('/share/m/{share_token}/quran/{number}', [ShareController::class, 'quranShow'])->name('share.quran-show');

// Protected App Routes for Teachers / Admins
Route::middleware('auth')->group(function () {
    // Dashboard & Big Search API
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/search', [DashboardController::class, 'search'])->name('search.api');

    // Manajemen Murid & Import Copas Excel
    Route::get('/students/import-excel', [StudentController::class, 'importExcel'])->name('students.import-excel');
    Route::post('/students/import-excel', [StudentController::class, 'processImportExcel'])->name('students.process-import-excel');
    Route::resource('students', StudentController::class);

    // Pencatatan Hafalan Checklist per Ayat
    Route::get('/memorization', [MemorizationController::class, 'index'])->name('memorization.index');
    Route::post('/memorization/toggle-verse', [MemorizationController::class, 'toggleVerse'])->name('memorization.toggle-verse');
    Route::post('/memorization/save-range', [MemorizationController::class, 'saveRange'])->name('memorization.save-range');

    // Laporan & Cetak A4 Landscape
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/print-a4-landscape', [ReportController::class, 'printA4Landscape'])->name('reports.print-a4-landscape');

    // Pengaturan Sekolah & Kop Laporan
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Baca Al-Qur'an & Progres Hafalan
    Route::get('/quran', [QuranController::class, 'index'])->name('quran.index');
    Route::get('/quran/{number}', [QuranController::class, 'show'])->name('quran.show');
});
