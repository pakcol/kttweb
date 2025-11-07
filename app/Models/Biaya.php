<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biaya extends Model
{
    use HasFactory;

    protected $table = 'biaya';

    protected $fillable = [
        'tgl',
        'jam',
        'biaya',
        'pembayaran',
        'keterangan',
        'username'
    ];

    protected $casts = [
        'tgl' => 'datetime',
        'biaya' => 'integer',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'username', 'username');
    }
}

