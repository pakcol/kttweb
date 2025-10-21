<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evi extends Model
{
    use HasFactory;

    protected $table = 'evi';

    protected $fillable = [
        'TGL_ISSUED',
        'JAM',
        'KODEBOKING',
        'AIRLINES',
        'NAMA',
        'RUTE1',
        'TGL_FLIGHT1',
        'RUTE2',
        'TGL_FLIGHT2',
        'HARGA',
        'NTA',
        'TOP_UP',
        'SALDO',
        'KETERANGAN',
        'USR'
    ];


    protected $casts = [
        'TGL_ISSUED' => 'date',
        'TGL_FLIGHT1' => 'date',
        'TGL_FLIGHT2' => 'date',
        'HARGA' => 'decimal:2',
        'NTA' => 'decimal:2',
        'TOP_UP' => 'decimal:2',
        'SALDO' => 'decimal:2',
    ];
}
