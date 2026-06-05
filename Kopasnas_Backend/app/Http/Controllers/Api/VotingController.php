<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Voting;
use App\Models\OpsiVoting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VotingController extends Controller
{
    public function index()
    {
        $data = Voting::with('opsi')
            ->withCount('votes')
            ->where('status', 'open') // hanya voting aktif
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'opsi' => 'required|array|min:2|max:3'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $voting = Voting::create([
            'judul' => $request->judul,
            'status' => 'open',
            'show_result' => false
        ]);

        foreach ($request->opsi as $opsi) {
            OpsiVoting::create([
                'voting_id' => $voting->id,
                'nama_opsi' => $opsi
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $voting
        ], 201);
    }

    public function show(string $id)
{
    // Tambahkan with('opsi') agar data pilihan kandidat ikut terbawa
    $data = Voting::with('opsi')->find($id);

    if (!$data) {
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $data // Data sekarang sudah berisi array opsi
    ], 200);
}

    public function result(string $id)
    {
        $voting = Voting::with('opsi.votes.anggota')->find($id);

        if (!$voting) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $result = $voting->opsi->map(function ($opsi) {
            return [
                'nama' => $opsi->nama_opsi,
                'total_vote' => $opsi->votes->count(),
                'voters' => $opsi->votes->map(function ($v) {
                    return $v->anggota ? $v->anggota->nama : 'Unknown';
                })
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'judul' => $voting->judul,
                'opsi' => $result
            ]
        ]);
    }

    public function bukaHasil(string $id)
    {
        $voting = Voting::find($id);

        if (!$voting) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $voting->update([
            'show_result' => true,
            'status' => 'close'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hasil dibuka'
        ]);
    }

    public function tutup(string $id)
    {
        $voting = Voting::find($id);

        if (!$voting) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $voting->update([
            'status' => 'close'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voting ditutup'
        ]);
    }

    public function update(Request $request, string $id)
{
    $voting = Voting::with('opsi')->find($id);

    if (!$voting) {
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    $validator = Validator::make($request->all(), [
        'judul' => 'required|string|max:255',
        'opsi' => 'nullable|array|min:2|max:3'
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // update judul
    $voting->update([
        'judul' => $request->judul
    ]);

    // update opsi kalau ada
    if ($request->opsi) {

        $voting->opsi()->delete();

        foreach ($request->opsi as $opsi) {
            $voting->opsi()->create([
                'nama_opsi' => $opsi
            ]);
        }
    }

    return response()->json([
        'success' => true,
        'data' => $voting->load('opsi')
    ]);
}

public function destroy(string $id)
{
    $voting = Voting::find($id);

    if (!$voting) {
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    // hapus opsi dulu biar aman
    $voting->opsi()->delete();

    $voting->delete();

    return response()->json([
        'success' => true,
        'message' => 'Voting berhasil dihapus'
    ]);
}


}
