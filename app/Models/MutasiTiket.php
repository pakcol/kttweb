<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiTiket extends Model
{
    protected $table = 'mutasi_tiket';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tiket_kode_booking',
        'tgl_issued',
        'tgl_bayar',
        'harga_bayar',
        'insentif',
        'jenis_bayar_id',
        'bank_id',
        'keterangan',
    ];

    public function tiket()
    {
        return $this->belongsTo(Tiket::class);
    }

    public function jenisBayar()
    {
        return $this->belongsTo(JenisBayar::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }


}
