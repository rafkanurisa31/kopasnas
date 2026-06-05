<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;

Route::get('/buat-admin-rahasia', function () {
    try {
        // 1. Trik Jitu: Jika kolom 'username' belum ada di tabel users, kita buatkan langsung!
        if (!Schema::hasColumn('users', 'username')) {
            Schema::table('users', function ($table) {
                $table->string('username')->nullable();
            });
        }

        // Hapus data admin lama biar tidak bentrok
        DB::table('users')->where('username', 'admin')->delete();
// Masukkan data admin baru dengan email berbeda agar tidak duplikat
        DB::table('users')->insert([
            'name' => 'Administrator',
            'username' => 'admin', // Ini yang dicari oleh form login kamu!
            'email' => 'adminbaru@gmail.com', // Diubah agar tidak bentrok
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return "Sistem Diperbarui! Akun admin sukses dibuat. Silakan login menggunakan Username: admin | Password: password123";
    } catch (\Exception $e) {
        return "Gagal memperbarui sistem. Eror: " . $e->getMessage();
    }
});
