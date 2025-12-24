<?php
// app/Models/Hutang.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hutang extends Model
{
    protected $table = 'hutang';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'tgl_lunas',
        'tiket_kode_booking',
        'pembayaran_online_id'
    ];
    
    protected $casts = [
        'tgl_lunas' => 'datetime',
    ];
    
    /**
     * Relasi ke Tiket
     */
    public function tiket(): BelongsTo
    {
        return $this->belongsTo(Tiket::class, 'tiket_kode_booking', 'kode_booking');
    }
    
    /**
     * Relasi ke PembayaranOnline
     */
    public function pembayaranOnline(): BelongsTo
    {
        return $this->belongsTo(PembayaranOnline::class, 'pembayaran_online_id');
    }
    
    /**
     * Relasi ke HistoryPembayaran
     */
    public function historyPembayaran(): HasMany
    {
        return $this->hasMany(HistoryPembayaran::class, 'hutang_id');
    }
}