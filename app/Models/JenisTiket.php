<?php
// app/Models/JenisTiket.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisTiket extends Model
{
    protected $table = 'jenis_tiket';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name_jenis',
        'saldo',
        'keterangan'
    ];
    
    /**
     * Relasi ke Tiket
     */
    public function tikets(): HasMany
    {
        return $this->hasMany(Tiket::class, 'jenis_tiket_id');
    }

    public function biaya()
    {
        return $this->hasMany(Biaya::class, 'id_jenis_tiket');
    }

}