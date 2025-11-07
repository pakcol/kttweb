<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evi extends Model
{
    use HasFactory;

    protected $table = 'evi';

    protected $fillable = [
        'tgl',
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
        'topup',
        'saldo',
        'keterangan',
        'username'
    ];

    protected $casts = [
        'tgl' => 'datetime',
        'jam' => 'datetime',
        'tglFlight1' => 'datetime',
        'tglFlight2' => 'datetime',
        'harga' => 'integer',
        'nta' => 'integer',
        'topup' => 'integer',
        'saldo' => 'integer',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'username', 'username');
    }
}
