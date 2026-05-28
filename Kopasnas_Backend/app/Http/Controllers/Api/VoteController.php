<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vote;
use App\Models\Voting;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function store(Request $request)
    {
        // cek apakah sudah vote
        $cek = Vote::where('anggota_id', $request->anggota_id)
            ->where('voting_id', $request->voting_id)
            ->exists();

        if ($cek) {

            return response()->json([
                'message' => 'Anda sudah vote di voting ini'
            ], 400);

        }

        // simpan vote
        $data = Vote::create([

            'anggota_id' => $request->anggota_id,

            'voting_id' => $request->voting_id,

            'opsi_id' => $request->opsi_id

        ]);

        return response()->json([

            'success' => true,

            'data' => $data

        ], 201);
    }

    // =====================
    // HASIL VOTING
    // =====================
    public function result(string $id)
    {
        $voting = Voting::with('opsi.votes')->find($id);

        if (!$voting) {

            return response()->json([
                'message' => 'Not found'
            ], 404);

        }

        $result = $voting->opsi->map(function ($opsi) {

            return [

                'nama' => $opsi->nama_opsi,

                'total_vote' => $opsi->votes->count()

            ];

        });

        return response()->json([

            'success' => true,

            'data' => [

                'id' => $voting->id,

                'judul' => $voting->judul,

                'opsi' => $result

            ]

        ]);
    }
}
