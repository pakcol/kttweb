<?php
// app/Models/Tiket.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tiket extends Model
{
    protected $table = 'tiket';
    protected $primaryKey = 'kode_booking';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'kode_booking',
        'tgl_issued',
        'name',
        'nta',
        'harga_jual',
        'diskon',
        'rute',
        'tgl_flight',
        'rute2',
        'tgl_flight2',
        'status',
        'jenis_tiket_id',
        'keterangan'
    ];
    
    protected $casts = [
        'tgl_issued' => 'datetime',
        'tgl_flight' => 'datetime',
        'tgl_flight2' => 'datetime',
        'nta' => 'integer',
        'harga_jual' => 'integer',
    ];
    
    /**
     * Relasi ke JenisTiket
     */
    public function jenisTiket(): BelongsTo
    {
        return $this->belongsTo(JenisTiket::class, 'jenis_tiket_id');
    }
    
    /**
     * Relasi ke Hutang
     */
    public function hutang(): HasOne
    {
        return $this->hasOne(Hutang::class, 'tiket_kode_booking', 'kode_booking');
    }
    
    /**
     * Relasi ke Subagent
     */
    public function subagent(): HasOne
    {
        return $this->hasOne(Subagent::class, 'tiket_kode_booking', 'kode_booking');
    }
    
    /**
     * Relasi ke Nota
     */
    public function nota()
    {
        return $this->hasOne(Nota::class, 'tiket_kode_booking', 'kode_booking');
    }
    
    /**
     * Relasi ke HistoryPembayaran
     */
    public function historyPembayaran(): HasMany
    {
        return $this->hasMany(HistoryPembayaran::class, 'tiket_kode_booking', 'kode_booking');
    }
}