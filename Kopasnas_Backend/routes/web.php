<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// 1. RUTE PEMBUAT AKUN (Gunakan email standar agar aman)
Route::get('/buat-admin-rahasia', function () {
    try {
        DB::table('users')->where('email', 'admin@gmail.com')->delete();

        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return "SABOTASE DATABASE SUKSES! Akun admin@gmail.com siap digunakan.";
    } catch (\Exception $e) {
        return "Gagal membuat akun: " . $e->getMessage();
    }
});

// 2. SABOTASE RUTE LOGIN FRONTEND (Membaca data 'username' dari Vue kamu)
Route::post('/api/login-admin', function (Request $request) {
    // Ambil input 'username' yang diketik di web Vercel
    $inputUser = $request->input('username');
    $inputPassword = $request->input('password');

    // Cari di database, apakah COCOK dengan email atau nama admin kita
    $user = DB::table('users')
              ->where('email', 'admin@gmail.com')
              ->first();

    if ($user && Hash::check($inputPassword, $user->password)) {
        // Buat token palsu/sementara agar Frontend Vue kamu lolos login
        return response()->json([
            'token' => 'sabotase_token_paskibra_2026',
            'role' => 'admin'
        ], 200);
    }

    return response()->json([
        'message' => 'Username tidak ditemukan'
    ], 404);
});
