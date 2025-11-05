<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlnPiutang extends Model
{
    use HasFactory;

    protected $table = 'pln_piutang';
    protected $fillable = [
        'tanggal',
        'jam',
        'id_pel',
        'harga_jual',
        'transaksi',
        'bayar',
        'nama_piutang',
        'top_up',
        'insentif',
        'saldo',
        'usr',
        'tgl_realisasi',
        'jam_realisasi'
    ];

    public $timestamps = false;
}
