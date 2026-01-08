<?php
// app/Models/Bank.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    protected $table = 'bank';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'saldo',
        'credit',
        'debit',
        'indicacs'
    ];
    
    protected $casts = [
        'saldo' => 'decimal:2',
        'credit' => 'decimal:2',
        'debit' => 'decimal:2',
    ];
    
    /**
     * Relasi ke JenisBayar
     */
    public function jenisBayar(): HasMany
    {
        return $this->hasMany(JenisBayar::class, 'bank_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

}