<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/buat-admin-rahasia', function () {
    try {
        // Cek apakah admin dengan email ini sudah ada
        $adminExist = DB::table('users')->where('email', 'admin@gmail.com')->first();

        if ($adminExist) {
            return "Akun admin sudah ada di database!";
        }

        // Masukkan data sesuai kolom yang pasti ada di database standar Laravel
        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com', // Kolom login utama kamu
            'password' => Hash::make('password123'), // Password kamu
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return "Akun sukses dibuat! Silakan login menggunakan Email: admin@gmail.com | Password: password123";
    } catch (\Exception $e) {
        return "Gagal membuat akun. Eror aslinya: " . $e->getMessage();
    }
});
