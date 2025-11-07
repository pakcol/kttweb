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
        'tgl',
        'id_pel',
        'harga_jual',
        'transaksi',
        'bayar',
        'nama_piutang',
        'top_up',
        'insentif',
        'saldo',
        'tgl_reralisasi',
        'jam_realisasi',
        'username'
    ];

    public $timestamps = false;

    protected $casts = [
        'tgl' => 'datetime',
        'tgl_reralisasi' => 'datetime',
        'jam_realisasi' => 'datetime',
        'id_pel' => 'integer',
        'harga_jual' => 'integer',
        'transaksi' => 'integer',
        'top_up' => 'integer',
        'insentif' => 'integer',
        'saldo' => 'integer',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'username', 'username');
    }

    // 🔽 Tambahkan scope untuk filter piutang
    public function scopePiutang(Builder $query)
    {
        return $query->where('bayar', 'PIUTANG');
    }
}
