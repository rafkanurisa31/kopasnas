<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpsiVoting extends Model
{
    use HasFactory;

    protected $fillable = [
        'voting_id',
        'nama_opsi'
    ];

    // relasi ke voting
   public function votes()
{
    return $this->hasMany(Vote::class, 'opsi_id');
}

public function voting()
{
    return $this->belongsTo(Voting::class, 'voting_id');
}
}
