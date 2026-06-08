<?php
// routes/api.php
// GANTI SELURUH ISI FILE INI

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiDashboardController;
use App\Http\Controllers\Api\ApiDivisiController;
use App\Http\Controllers\Api\ApiStaffController;
use App\Http\Controllers\Api\ApiKriteriaController;
use App\Http\Controllers\Api\ApiBobotController;
use App\Http\Controllers\Api\ApiSkalaController;
use App\Http\Controllers\Api\ApiPenilaianController;
use App\Http\Controllers\Api\ApiHasilController;
use Illuminate\Support\Facades\Route;

// ── PUBLIC (tanpa auth) ───────────────────────────────────────────
Route::post('/login', [ApiAuthController::class, 'login']);

// ── PROTECTED (perlu token) ───────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [ApiAuthController::class, 'logout']);

    // Dashboard
    Route::get('/dashboard', [ApiDashboardController::class, 'index']);

    // Master data
    Route::apiResource('divisi',   ApiDivisiController::class)->except(['show']);
    Route::apiResource('staff',    ApiStaffController::class)->except(['show']);
    Route::apiResource('kriteria', ApiKriteriaController::class)->except(['show']);

    // Bobot
    Route::get('/bobot',  [ApiBobotController::class, 'index']);
    Route::post('/bobot', [ApiBobotController::class, 'store']);

    // Skala
    Route::get('/skala',                          [ApiSkalaController::class, 'index']);
    Route::post('/skala',                         [ApiSkalaController::class, 'store']);
    Route::delete('/skala/{skalaPenilaian}',       [ApiSkalaController::class, 'destroy']);

    // Penilaian
    Route::get('/penilaian',             [ApiPenilaianController::class, 'index']);
    Route::post('/penilaian',            [ApiPenilaianController::class, 'store']);
    Route::put('/penilaian/{staff}',     [ApiPenilaianController::class, 'update']);
    Route::get('/periode',               [ApiPenilaianController::class, 'periodeList']);

    // Hasil MABAC
    Route::get('/hasil',              [ApiHasilController::class, 'index']);
    Route::post('/hasil/hitung',      [ApiHasilController::class, 'hitung']);
    Route::get('/hasil/{divisi}',     [ApiHasilController::class, 'detail']);
});
