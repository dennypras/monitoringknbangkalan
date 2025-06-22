<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PerkaraController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/berkas-perkara', [PerkaraController::class, 'store']);
    Route::put('/berkas-perkara/{id}', [PerkaraController::class, 'updateKategori']);
    Route::post('/berkas-perkara/{id}', [PerkaraController::class, 'updateKategori']);
    Route::delete('/berkas-perkara/{id}', [PerkaraController::class, 'destroy']);
});

Route::get('/berkas-perkara', [PerkaraController::class, 'index']);
Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/dashboard/statistik', [DashboardController::class, 'getStatistik']);
Route::get('/rekapitulasi', [DashboardController::class, 'rekapitulasi']);
Route::get('/berkas-perkara/{id}', [PerkaraController::class, 'show']);