<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voting extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'status',
        'show_result'
    ];

    // 🔥 RELASI YANG BENAR
    public function opsi()
    {
        return $this->hasMany(OpsiVoting::class, 'voting_id');
    }

    // optional: relasi ke vote kalau nanti dipakai
    public function votes()
    {
        return $this->hasMany(Vote::class, 'voting_id');
    }
}
