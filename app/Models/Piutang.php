<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piutang extends Model
{
    protected $fillable = ['nama', 'jumlah'];

    public function mutasi()
    {
        return $this->hasMany(MutasiTiket::class);
    }
}

