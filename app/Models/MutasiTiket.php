<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiTiket extends Model
{
    protected $table    = 'mutasi_tiket';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tiket_kode_booking',
        'tgl_issued',
        'tgl_bayar',
        'harga_bayar',
        'insentif',
        'jenis_bayar_id',
        'bank_id',
        'piutang_id',
        'keterangan',
    ];

    // =====================================================
    // RELASI
    // =====================================================

    public function piutang()
    {
        return $this->belongsTo(Piutang::class);
    }

    public function tiket()
    {
        return $this->belongsTo(Tiket::class, 'tiket_kode_booking', 'kode_booking');
    }

    public function jenisBayar()
    {
        return $this->belongsTo(JenisBayar::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function jenisTiket()
    {
        return $this->hasOneThrough(
            JenisTiket::class,
            Tiket::class,
            'kode_booking',      // FK di tiket
            'id',                // FK di jenis_tiket
            'tiket_kode_booking',// local key di mutasi_tiket
            'jenis_tiket_id'     // local key di tiket
        );
    }
}
