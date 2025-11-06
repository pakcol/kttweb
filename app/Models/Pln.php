<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Pln extends Model
{
    use HasFactory;

    protected $table = 'pln';

    protected $fillable = [
        'no_pel',
        'pulsa',
        'nta',
        'tgl',
        'bayar',
        'nama_piutang',
        'tanggal',
        'jam',
        'id_pel',
        'harga_jual',
        'transaksi',
        'top_up',
        'insentif',
        'saldo',
        'usr',
        'tgl_realisasi',
        'jam_realisasi'
    ];

    public $timestamps = false;

    // 🔽 Tambahkan scope untuk filter piutang
    public function scopePiutang(Builder $query)
    {
        return $query->where('bayar', 'PIUTANG');
    }
}
