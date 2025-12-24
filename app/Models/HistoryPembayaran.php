<?php
// app/Models/HistoryPembayaran.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoryPembayaran extends Model
{
    protected $table = 'history_pembayaran';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'tiket_kode_booking',
        'nota_id',
        'hutang_id',
        'jumlah',
        'keterangan',
        'tipe'
    ];
    
    protected $casts = [
        'jumlah' => 'decimal:2',
    ];
    
    /**
     * Relasi ke Tiket
     */
    public function tiket(): BelongsTo
    {
        return $this->belongsTo(Tiket::class, 'tiket_kode_booking', 'kode_booking');
    }
    
    /**
     * Relasi ke Nota
     */
    public function nota(): BelongsTo
    {
        return $this->belongsTo(Nota::class, 'nota_id');
    }
    
    /**
     * Relasi ke Hutang
     */
    public function hutang(): BelongsTo
    {
        return $this->belongsTo(Hutang::class, 'hutang_id');
    }
    
    /**
     * Scope untuk tipe tertentu
     */
    public function scopeTipe($query, $tipe)
    {
        return $query->where('tipe', $tipe);
    }
}