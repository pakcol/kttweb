<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pln extends Model
{
    use HasFactory;

    protected $table = 'plns'; 

    protected $fillable = [
        'no_pel',
        'pulsa',
        'nta',
        'tgl',
        'bayar',
        'nama_piutang'
    ];
}
