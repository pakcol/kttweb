<?php
// app/Models/Subagent.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subagent extends Model
{
    protected $table = 'subagents';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'tiket_kode_booking',
        'saldo',
        'indicacs'
    ];
    
    protected $casts = [
        'saldo' => 'integer',
    ];
    
    /**
     * Relasi ke Tiket
     */
    public function tiket(): BelongsTo
    {
        return $this->belongsTo(Tiket::class, 'tiket_kode_booking', 'kode_booking');
    }
}