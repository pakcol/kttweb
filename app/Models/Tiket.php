<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory;

    protected $table = 'ticket';

    protected $fillable = [
        'tglIssued',
        'jam',
        'kodeBooking',
        'airlines',
        'nama',
        'rute1',
        'tglFlight1',
        'rute2',
        'tglFlight2',
        'harga',
        'nta',
        'diskon',
        'komisi',
        'pembayaran',
        'namaPiutang',
        'tglRealisasi',
        'jamRealisasi',
        'nilaiRefund',
        'keterangan',
        'username'
    ];

    protected $casts = [
        'tglIssued' => 'datetime',
        'tglFlight1' => 'datetime',
        'tglFlight2' => 'datetime',
        'tglRealisasi' => 'datetime',
        'harga' => 'integer',
        'nta' => 'integer',
        'diskon' => 'integer',
        'komisi' => 'integer',
        'nilaiRefund' => 'integer',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'username', 'username');
    }
}
