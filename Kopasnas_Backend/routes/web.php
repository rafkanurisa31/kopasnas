<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;

Route::get('/buat-admin-rahasia', function () {
    try {
        // 1. Pastikan kolom username ada di database
        if (!Schema::hasColumn('users', 'username')) {
            Schema::table('users', function ($table) {
                $table->string('username')->nullable();
            });
        }

        // 2. Bersihkan data lama agar TIDAK ADA eror duplikat/unique constraint lagi
        DB::table('users')->where('username', 'admin')->delete();
        DB::table('users')->where('email', 'admin@gmail.com')->delete();
        DB::table('users')->where('email', 'adminbaru@gmail.com')->delete();

        // 3. AKUN PERTAMA: Login menggunakan kata 'admin'
        DB::table('users')->insert([
            'name' => 'Admin Utama',
            'username' => 'admin',
            'email' => 'adminbaru@gmail.com',
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. AKUN KEDUA: Login menggunakan kata 'admin@gmail.com'
        DB::table('users')->insert([
            'name' => 'Admin Email',
            'username' => 'admin_email',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return "SABOTASE BERHASIL! Semua data lama dibersihkan. Akun baru siap digunakan.";
    } catch (\Exception $e) {
        return "Gagal total. Erornya: " . $e->getMessage();
    }
});
