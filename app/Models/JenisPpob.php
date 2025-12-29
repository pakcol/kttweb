<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPpob extends Model
{
    protected $table = 'jenis_ppob';

    protected $fillable = [
        'jenis_ppob',
        'saldo',
        'keterangan'
    ];

    public function jenisPpob()
    {
        return $this->belongsTo(JenisPpob::class);
    }

}

