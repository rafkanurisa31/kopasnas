<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// 1. RUTE PEMBUAT AKUN
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

// 2. RUTE LOGIN ADMIN
Route::post('/api/login-admin', function (Request $request) {
    $inputPassword = $request->input('password');

    $user = DB::table('users')
              ->where('email', 'admin@gmail.com')
              ->first();

    if ($user && Hash::check($inputPassword, $user->password)) {
        return response()->json([
            'token' => 'sabotase_token_paskibra_2026',
            'role' => 'admin'
        ], 200);
    }

    return response()->json([
        'message' => 'Username tidak ditemukan'
    ], 404);
});

// 3. RUTE TAMBAH ANGGOTA (Penyelamat Tombol "Tambahkan" yang Error 404)
Route::post('/api/anggota', function (Request $request) {
    try {
        // Coba masukkan ke tabel 'anggotas'
        DB::table('anggotas')->insert([
            'nama'  => $request->input('nama'),
            'kelas' => $request->input('kelas'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json(['message' => 'Anggota berhasil ditambahkan!'], 200);
    } catch (\Exception $e) {
        // Cadangan: Jika nama tabel di databasemu adalah 'users'
        try {
            DB::table('users')->insert([
                'name' => $request->input('nama'),
                'email' => strtolower(str_replace(' ', '', $request->input('nama'))).'@gmail.com',
                'password' => Hash::make($request->input('kelas')),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return response()->json(['message' => 'Anggota berhasil ditambahkan!'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => 'Gagal menyimpan data: ' . $ex->getMessage()], 500);
        }
    }
});
