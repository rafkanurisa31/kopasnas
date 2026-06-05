<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::get('/buat-admin-rahasia', function () {
    // Cek apakah user admin sudah ada supaya tidak duplikat
    $adminExist = User::where('username', 'admin')->first();

    if ($adminExist) {
        return "Akun admin sudah ada!";
    }

    // Membuat akun admin baru
    User::create([
        'name' => 'Administrator',
        'username' => 'admin',
        'password' => Hash::make('admin123'), // Ganti sesuai password yang kamu mau
        'role' => 'admin', // Sesuaikan kolom role jika aplikasimu memakainya
    ]);

    return "Akun admin berhasil dibuat! Username: admin | Password: admin123";
});
