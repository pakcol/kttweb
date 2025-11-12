<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mandiri extends Model
{
    use HasFactory;

    protected $table = 'mandiri';
    
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