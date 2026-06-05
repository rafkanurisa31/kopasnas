<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/buat-admin-rahasia', function () {
    try {
        // Kita masukkan data murni menggunakan SQL DB Table
        DB::table('users')->insert([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@gmail.com', // Kita masukkan dua-duanya biar aman jika tabelmu pakai email/username
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return "Akun sukses dibuat via SQL murni! Silakan coba login.";
    } catch (\Exception $e) {
        // Jika masih gagal, kode ini akan memaksa memunculkan pesan eror aslinya di layar browser kamu!
        return "Gagal membuat akun. Eror aslinya: " . $e->getMessage();
    }
});
