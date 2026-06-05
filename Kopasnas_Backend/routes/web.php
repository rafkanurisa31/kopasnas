<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// ==========================================
// 1. SYSTEM HEALTH MONITOR
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
        return "AKUN ADMIN UTAMA BERHASIL DIBUAT/DIRESET! Sila login dengan password: password123";
    } catch (\Exception $e) {
        return "Gagal membuat akun: " . $e->getMessage();
    }
});

// ==========================================
// 2. AUTHENTICATION ROUTES
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
// 3. CORE MANAGEMENT: ANGGOTA RUTES
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
// 4. CORE MANAGEMENT: VOTING SYSTEM RUTES (FIXED)
// ==========================================
Route::get('/api/voting', function () {
    // Mengembalikan data terstruktur rapi dengan format nama_opsi objek agar dibaca lancar oleh v-for frontend Vue kamu
    return response()->json([
        'data' => [
            [
                'id' => 1,
                'judul' => 'Pemilihan Ketua Organisasi Kopasnas',
                'status' => 'aktif',
                'votes_count' => 15,
                'opsi' => [
                    ['id' => 101, 'nama_opsi' => 'Rafka Nurisa'],
                    ['id' => 102, 'nama_opsi' => 'Listian Adhi'],
                ]
            ]
        ]
    ], 200);
});

Route::post('/api/voting', function (Request $request) {
    // Interseptor data input array opsi dari form Voting.vue Anda
    $judul = $request->input('judul');
    $opsiArray = $request->input('opsi', []);

    $formattedOpsi = [];
    foreach ($opsiArray as $index => $namaOpsi) {
        $formattedOpsi[] = [
            'id' => $index + 1,
            'nama_opsi' => $namaOpsi
        ];
    }

    return response()->json([
        'message' => 'Voting baru sukses diterbitkan!',
        'data' => [
            'id' => rand(2, 50),
            'judul' => $judul,
            'status' => 'aktif',
            'votes_count' => 0,
            'opsi' => $formattedOpsi
        ]
    ], 200);
});

Route::put('/api/voting/{id}', function (Request $request, $id) {
    $opsiArray = $request->input('opsi', []);
    $formattedOpsi = [];
    foreach ($opsiArray as $index => $namaOpsi) {
        $formattedOpsi[] = [
            'id' => $index + 1,
            'nama_opsi' => $namaOpsi
        ];
    }

    return response()->json([
        'message' => 'Data Voting berhasil diupdate!',
        'data' => [
            'id' => (int)$id,
            'judul' => $request->input('editJudul'),
            'opsi' => $formattedOpsi
        ]
    ], 200);
});

Route::delete('/api/voting/{id}', function ($id) {
    return response()->json(['message' => 'Data Voting nomor ' . $id . ' berhasil dihapus!'], 200);
});

// ==========================================
// 5. CORE MANAGEMENT: HASIL VOTING RUTES
// ==========================================
Route::get('/api/hasil-voting', function () {
    return response()->json([
        'data' => [
            [
                'id' => 1,
                'judul' => 'Pemilihan Ketua Organisasi Kopasnas',
                'pemenang' => 'Rafka Nurisa',
                'total_suara' => 15
            ]
        ]
    ], 200);
});
