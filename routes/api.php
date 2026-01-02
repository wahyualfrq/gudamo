<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BarangMasukController;
use App\Http\Controllers\Api\BarangKeluarController;
use App\Http\Controllers\ManageController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route ::post('/user', [AuthController::class, 'user']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

});

// BarangMasuk
Route::get('/barang-masuk/{id}', [BarangMasukController::class, 'show']);
Route::put('/barang-masuk/{id}', [BarangMasukController::class, 'update']);
Route::delete('/barang-masuk/{id}', [BarangMasukController::class, 'destroy']);

// BarangKeluar
Route::put('/barang-keluar/{id}', [BarangKeluarController::class, 'update']);
Route::delete('/barang-keluar/{id}', [BarangKeluarController::class, 'destroy']);
Route::post('/barang-keluar', [BarangKeluarController::class, 'store']);
// detail barnag keluar dan masuk
Route::get('/barang-masuk', [BarangMasukController::class, 'index']);
Route::post('/barang-masuk', [BarangMasukController::class, 'store']);
Route::get('/barang-keluar/{id}', [BarangKeluarController::class, 'show']);
Route::get('/barang-keluar', [BarangKeluarController::class, 'index']);
// barang yang menipis
Route :: get('/barang-menipis', [ManageController::class, 'menipis']);
