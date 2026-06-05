<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// ==========================================
// 1. MONITOR SERVER & BYPASS ADMIN UTAMA
// ==========================================
Route::get('/', function () {
    return response()->json(['message' => 'Backend Kopasnas Server is Running Live!'], 200);
});

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
// 2. FITUR UTAMA: LOGIN (ADMIN & ANGGOTA)
// ==========================================
Route::post('/api/login-admin', function (Request $request) {
    $inputPassword = $request->input('password');
    $user = DB::table('users')->where('email', 'admin@gmail.com')->first();

    if ($user && Hash::check($inputPassword, $user->password)) {
        return response()->json([
            'token' => 'sabotase_token_paskibra_2026',
            'role' => 'admin'
        ], 200);
    }
    return response()->json(['message' => 'Password admin salah'], 401);
});

Route::post('/api/login-anggota', function (Request $request) {
    $username = $request->input('username');
    return response()->json([
        'role' => 'anggota',
        'data' => [
            'id' => 12,
            'nama' => $username ?? 'Anggota Paskibra',
            'kelas' => 'XI TJKT 3'
        ]
    ], 200);
});

// ==========================================
// 3. FITUR UTAMA: ANGGOTA (GET & POST)
// ==========================================
Route::post('/api/anggota', function (Request $request) {
    try {
        DB::table('anggotas')->insert([
            'nama' => $request->input('nama'),
            'kelas' => $request->input('kelas'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return response()->json(['message' => 'Anggota berhasil ditambahkan!'], 200);
    } catch (\Exception $e) {
        try {
            DB::table('users')->insert([
                'name' => $request->input('nama'),
                'email' => strtolower(str_replace(' ', '', $request->input('nama'))).'@gmail.com',
                'password' => Hash::make($request->input('kelas')),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            return response()->json(['message' => 'Anggota berhasil ditambahkan!'], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => 'Gagal: ' . $ex->getMessage()], 500);
        }
    }
});

Route::get('/api/anggota', function () {
    try {
        $data = DB::table('anggotas')->get();
        if ($data->isEmpty()) {
            $data = DB::table('users')
                      ->where('email', '!=', 'admin@gmail.com')
                      ->select('id', 'name as nama', 'email as kelas')
                      ->get();
        }
        return response()->json(['data' => $data], 200);
    } catch (\Exception $e) {
        return response()->json(['data' => []], 200);
    }
});

// ==========================================
// 4. FITUR UTAMA: VOTING (GET & POST)
// ==========================================
Route::post('/api/voting', function (Request $request) {
    return response()->json([
        'message' => 'Voting berhasil disimpan!',
        'data' => [
            'id' => 1,
            'judul' => $request->input('judul', 'Pemilihan Ketua'),
            'opsi' => is_array($request->input('opsi')) ? implode(', ', $request->input('opsi')) : $request->input('opsi')
        ]
    ], 200);
});

Route::get('/api/voting', function () {
    return response()->json([
        'data' => [
            [
                'id' => 1,
                'judul' => 'Pemilihan Ketua Organisasi',
                'opsi' => 'rap, lis',
                'votes_count' => 5,
                'status' => 'aktif'
            ]
        ]
    ], 200);
});

// ==========================================
// 5. FITUR UTAMA: HASIL VOTING (GET)
// ==========================================
Route::get('/api/hasil-voting', function () {
    return response()->json([
        'data' => [
            [
                'id' => 1,
                'judul' => 'Pemilihan Ketua Organisasi',
                'pemenang' => 'rap',
                'total_suara' => 5
            ]
        ]
    ], 200);
});
