<?php
// app/Models/Nota.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nota extends Model
{
    protected $table = 'nota';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'tgl_issued',
        'tgl_bayar',
        'harga_bayar',
        'jenis_bayar_id',
        'bank_id',
        'pembayaran_online_id',
        'tiket_kode_booking'
    ];
    
    protected $casts = [
        'tgl_issued' => 'datetime',
        'tgl_bayar' => 'datetime',
        'harga_bayar' => 'integer',
    ];
    
    /**
     * Relasi ke Tiket
     */
    public function tiket()
    {
        return $this->belongsTo(Tiket::class, 'tiket_kode_booking', 'kode_booking');
    }
    
    // Relasi Ke Bank
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * Relasi ke JenisBayar
     */
    public function jenisBayar()
    {
        return $this->belongsTo(JenisBayar::class);
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
        return $this->hasMany(HistoryPembayaran::class, 'nota_id');
    }
}