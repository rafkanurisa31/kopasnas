<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// ==========================================
// 1. RUTE PEMBUAT AKUN ADMIN
// ==========================================
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

// ==========================================
// 2. RUTE LOGIN ADMIN (POST /api/login-admin)
// ==========================================
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

    return response()->json(['message' => 'Username atau password admin salah'], 404);
});

// ==========================================
// 3. RUTE TAMBAH ANGGOTA (POST /api/anggota)
// ==========================================
Route::post('/api/anggota', function (Request $request) {
    $nama = $request->input('nama');
    $kelas = $request->input('kelas');

    try {
        // Coba masukkan ke tabel anggotas
        DB::table('anggotas')->insert([
            'nama'  => $nama,
            'kelas' => $kelas,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json(['message' => 'Anggota berhasil ditambahkan!'], 200);
    } catch (\Exception $e) {
        // Cadangan jika tabelnya adalah 'users'
        try {
            DB::table('users')->insert([
                'name' => $nama,
                'email' => strtolower(str_replace(' ', '', $nama)).'@gmail.com',
                'password' => Hash::make($kelas),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return response()->json(['message' => 'Anggota berhasil ditambahkan ke tabel users!'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => 'Gagal menyimpan: ' . $ex->getMessage()], 500);
        }
    }
});

// ==========================================
// 4. RUTE TAMPILKAN ANGGOTA (GET /api/anggota)
// ==========================================
Route::get('/api/anggota', function () {
    try {
        $anggotas = DB::table('anggotas')->get();

        if ($anggotas->isEmpty()) {
            // Mengambil dari tabel users selain admin utama
            $anggotas = DB::table('users')
                          ->where('email', '!=', 'admin@gmail.com')
                          ->select('id', 'name as nama', 'email as kelas')
                          ->get();
        }

        // Format dibungkus dalam objek 'data' sesuai permintaan Vue kamu
        return response()->json([
            'data' => $anggotas
        ], 200);

    } catch (\Exception $e) {
        return response()->json(['data' => []], 200);
    }
});

// ==========================================
// 5. RUTE LOGIN ANGGOTA (POST /api/login-anggota)
// ==========================================
Route::post('/api/login-anggota', function (Request $request) {
    $nama = $request->input('nama');
    $kelas = $request->input('kelas');

    // Cari berdasarkan nama di tabel anggotas atau users
    $anggota = DB::table('anggotas')->where('nama', $nama)->first();

    if (!$anggota) {
        $anggota = DB::table('users')->where('name', $nama)->first();
        if ($anggota) {
            $anggota->nama = $anggota->name;
            $anggota->kelas = "XI TJKT 3"; // fallback kelas default
        }
    }

    if ($anggota) {
        return response()->json([
            'role' => 'anggota',
            'data' => [
                'id' => $anggota->id,
                'nama' => $anggota->nama,
                'kelas' => $anggota->kelas ?? 'XI'
            ]
        ], 200);
    }

    return response()->json(['message' => 'Data anggota tidak ditemukan'], 404);
});
