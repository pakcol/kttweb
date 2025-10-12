<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory;

    protected $fillable = [
        'tgl_issued',
        'jam',
        'kode_booking',
        'airlines',
        'nama',
        'rute1',
        'tgl_flight1',
        'rute2',
        'tgl_flight2',
        'harga',
        'nta',
        'diskon',
        'komisi',
        'pembayaran',
        'nama_piutang',
        'tgl_realisasi',
        'nilai_refund',
        'keterangan',
        'usr'
    ];

    protected $casts = [
        'tgl_issued' => 'date',
        'tgl_flight1' => 'date',
        'tgl_flight2' => 'date',
        'tgl_realisasi' => 'date',
        'harga' => 'decimal:2',
        'nta' => 'decimal:2',
        'diskon' => 'decimal:2',
        'komisi' => 'decimal:2',
        'nilai_refund' => 'decimal:2'
    ];
}