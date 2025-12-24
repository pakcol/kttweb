<?php
// app/Models/PembayaranOnline.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PembayaranOnline extends Model
{
    protected $table = 'pembayaran_online';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'tgl',
        'tgl_pel',
        'nta',
        'harga_jual',
        'saldo'
    ];
    
    protected $casts = [
        'tgl' => 'datetime',
        'tgl_pel' => 'date',
        'nta' => 'integer',
        'harga_jual' => 'integer',
        'saldo' => 'integer',
    ];
    
    /**
     * Relasi ke Hutang
     */
    public function hutangs(): HasMany
    {
        return $this->hasMany(Hutang::class, 'pembayaran_online_id');
    }
    
    /**
     * Relasi ke Nota
     */
    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class, 'pembayaran_online_id');
    }
}