<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'anggota_id',
        'voting_id',
        'opsi_id'
    ];

    // 1 vote milik 1 anggota
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    // vote milik 1 opsi voting
    public function opsi()
    {
        return $this->belongsTo(OpsiVoting::class, 'opsi_id');
    }

    // vote milik voting utama
    public function voting()
    {
        return $this->belongsTo(Voting::class, 'voting_id');
    }
}
