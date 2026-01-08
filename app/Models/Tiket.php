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
        'nta',
        'diskon',
        'komisi',
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
        'diskon' => 'integer',
    ];
    
    /**
     * Relasi ke JenisTiket
     */
    public function jenisTiket(): BelongsTo
    {
        return $this->belongsTo(JenisTiket::class, 'jenis_tiket_id');
    }
    
    /**
     * Relasi ke Subagent
     */
    public function subagent(): HasOne
    {
        return $this->hasOne(Subagent::class, 'tiket_kode_booking', 'kode_booking');
    }
    
    /**
     * Relasi ke Mutasi Tiket
     */
    public function mutasiTiket()
    {
        return $this->hasOne(MutasiTiket::class, 'tiket_kode_booking', 'kode_booking');
    }


    public function subagentHistories()
    {
        return $this->hasMany(SubagentHistory::class, 'kode_booking', 'kode_booking');
    }

    public function getPembayaranLabelAttribute()
    {
        // tidak ada mutasi / jenis bayar
        if (!$this->mutasiTiket || !$this->mutasiTiket->jenisBayar) {
            return '-';
        }

        $jenis = strtoupper($this->mutasiTiket->jenisBayar->jenis);

        // khusus BANK → tambahkan nama bank
        if ($jenis === 'BANK') {
            return 'BANK ' . ($this->mutasiTiket->bank->name ?? '');
        }

        // selain BANK → tampilkan jenis saja
        return $jenis;
    }
}