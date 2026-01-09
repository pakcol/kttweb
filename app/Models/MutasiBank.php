<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiBank extends Model
{
    protected $table = 'mutasi_bank';

    protected $fillable = [
        'bank_id',
        'tanggal',
        'debit',
        'kredit',
        'saldo',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'debit'   => 'integer',
        'kredit'  => 'integer',
        'saldo'   => 'integer',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
}

