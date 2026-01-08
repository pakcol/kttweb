<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopupHistory extends Model
{
    protected $table = 'topup_histories';

    protected $fillable = [
        'tgl_issued',
        'transaksi',
        'jenis_tiket_id',
        'subagent_id',
        'jenis_bayar_id',
        'bank_id',
        'keterangan',
    ];

    protected $casts = [
        'tgl_issued' => 'date',
        'transaksi'  => 'integer',
    ];

    /* ================= RELATION ================= */

    public function jenisTiket()
    {
        return $this->belongsTo(JenisTiket::class, 'jenis_tiket_id');
    }

    public function subagent()
    {
        return $this->belongsTo(Subagent::class, 'subagent_id');
    }

    public function jenisBayar()
    {
        return $this->belongsTo(JenisBayar::class, 'jenis_bayar_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
}
