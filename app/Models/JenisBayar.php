<?php
// app/Models/JenisBayar.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisBayar extends Model
{
    protected $table = 'jenis_bayar';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'jenis',
        'indicacs'
    ];
    
    /**
     * Relasi ke Nota
     */
    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class, 'jenis_bayar_id');
    }
    
    /**
     * Relasi ke Biaya
     */
    public function biayas(): HasMany
    {
        return $this->hasMany(Biaya::class, 'jenis_bayar_id');
    }
}