<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BTN extends Model
{
    use HasFactory;

    protected $table = 'btn';
    
    protected $fillable = [
        'tgl',
        'keterangan',
        'credit',
        'debit',
        'saldo',
        'username'
    ];

    protected $casts = [
        'tgl' => 'datetime',
        'credit' => 'decimal:2',
        'debit' => 'decimal:2',
        'saldo' => 'decimal:2'
    ];
}