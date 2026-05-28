<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AnggotaController;
use App\Http\Controllers\Api\CalonAnggotaController;
use App\Http\Controllers\Api\VotingController;
use App\Http\Controllers\Api\OpsiVotingController;
use App\Http\Controllers\Api\VoteController;
use App\Http\Controllers\Api\AuthController;

# =========================
# 🔓 PUBLIC (TANPA LOGIN)
# =========================

// fallback login route
Route::any('/login', function () {
    return response()->json([
        'message' => 'Silakan login terlebih dahulu'
    ], 401);
})->name('login');


// =========================
// AUTH
// =========================

// auth login
Route::post('/login-admin', [AuthController::class, 'loginAdmin']);
Route::post('/login-anggota', [AuthController::class, 'loginAnggota']);


// create admin awal
Route::post('/admin', [AdminController::class, 'store']);


// =========================
// PUBLIC ANGGOTA
// =========================

// semua orang bisa lihat anggota
Route::get('/anggota', [AnggotaController::class, 'index']);


// =========================
// PUBLIC VOTING
// =========================

// lihat voting
Route::get('/voting', [VotingController::class, 'index']);

// detail voting
Route::get('/voting/{id}', [VotingController::class, 'show']);

// hasil voting
Route::get('/voting/{id}/result', [VotingController::class, 'result']);

// submit vote
Route::post('/vote', [VoteController::class, 'store']);



# =========================
# 🔐 LOGIN REQUIRED
# =========================
Route::middleware('auth:sanctum')->group(function () {

    // logout
    Route::post('/logout', [AuthController::class, 'logout']);



    // =========================
    // ADMIN MANAGEMENT
    // =========================

    Route::apiResource('admin', AdminController::class)
        ->except(['store']);



    // =========================
    // KHUSUS ADMIN
    // =========================

    // CRUD anggota
    Route::post('/anggota', [AnggotaController::class, 'store']);

    Route::put('/anggota/{id}', [AnggotaController::class, 'update']);

    Route::delete('/anggota/{id}', [AnggotaController::class, 'destroy']);



    // calon anggota
    Route::apiResource('calon-anggota', CalonAnggotaController::class);



    // voting CRUD admin
    Route::apiResource('voting', VotingController::class)
        ->except(['index', 'show']);



    // opsi voting CRUD
    Route::apiResource('opsi-voting', OpsiVotingController::class);



    // voting control
    Route::post('/voting/{id}/buka-hasil', [VotingController::class, 'bukaHasil']);

    Route::post('/voting/{id}/tutup', [VotingController::class, 'tutup']);

});
