<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BobotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\HasilController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SkalaPenilaianController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

// ── HALAMAN PUBLIK (tanpa login) ─────────────────────────────
Route::get('/', [PublicController::class, 'index'])->name('public.index');

// ── AUTH ─────────────────────────────────────────────────────
Route::get('/admin/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/admin/logout',[AuthController::class, 'logout'])->name('logout');

// ── ADMIN (perlu login) ──────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Divisi
    Route::resource('divisi', DivisiController::class)->except(['show']);

    // Staff
    Route::resource('staff', StaffController::class)->except(['show']);

    // Kriteria
    Route::resource('kriteria', KriteriaController::class)->except(['show']);

    // Bobot Kriteria
    Route::get('/bobot',  [BobotController::class, 'index'])->name('bobot.index');
    Route::post('/bobot', [BobotController::class, 'store'])->name('bobot.store');

    // Skala Penilaian
    Route::get('/skala',                        [SkalaPenilaianController::class, 'index'])->name('skala.index');
    Route::post('/skala',                       [SkalaPenilaianController::class, 'store'])->name('skala.store');
    Route::delete('/skala/{skalaPenilaian}',    [SkalaPenilaianController::class, 'destroy'])->name('skala.destroy');

    // Penilaian
    Route::get('/penilaian',               [PenilaianController::class, 'index'])->name('penilaian.index');
    Route::get('/penilaian/create',        [PenilaianController::class, 'create'])->name('penilaian.create');
    Route::post('/penilaian',              [PenilaianController::class, 'store'])->name('penilaian.store');
    Route::get('/penilaian/{staff}/edit',  [PenilaianController::class, 'edit'])->name('penilaian.edit');
    Route::put('/penilaian/{staff}',       [PenilaianController::class, 'update'])->name('penilaian.update');

    // Hasil MABAC
    Route::get('/hasil',            [HasilController::class, 'index'])->name('hasil.index');
    Route::post('/hasil/hitung',    [HasilController::class, 'hitung'])->name('hasil.hitung');
    Route::get('/hasil/{divisi}',   [HasilController::class, 'detail'])->name('hasil.detail');
    Route::get('/hasil-export',     [HasilController::class, 'export'])->name('hasil.export');
});

// Redirect lama (jaga kompatibilitas)
Route::get('/dashboard', fn() => redirect()->route('admin.dashboard'))->middleware('auth');
