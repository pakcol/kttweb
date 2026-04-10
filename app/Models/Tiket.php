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
        'komisi',
        'rute',
        'tgl_flight',
        'rute2',
        'tgl_flight2',
        'status',
        'jenis_tiket_id',
        'keterangan',
        'nilai_refund',
        'tgl_realisasi'
    ];
    
    protected $casts = [
        'tgl_issued'    => 'datetime',
        'tgl_flight'    => 'datetime',
        'tgl_flight2'   => 'datetime',
        'tgl_realisasi' => 'datetime',
        'nta'           => 'decimal:2',
        'harga_jual'    => 'decimal:2',
        'diskon'        => 'decimal:2',
        'komisi'        => 'decimal:2',
        'nilai_refund'  => 'decimal:2',
    ];

    // =====================================================
    // RELASI
    // =====================================================

    public function jenisTiket(): BelongsTo
    {
        return $this->belongsTo(JenisTiket::class, 'jenis_tiket_id');
    }

    public function subagent(): HasOne
    {
        return $this->hasOne(Subagent::class, 'tiket_kode_booking', 'kode_booking');
    }

    public function mutasiTiket(): HasOne
    {
        return $this->hasOne(MutasiTiket::class, 'tiket_kode_booking', 'kode_booking');
    }

    public function subagentHistories(): HasMany
    {
        return $this->hasMany(SubagentHistory::class, 'kode_booking', 'kode_booking');
    }

    // =====================================================
    // ACCESSOR — ambil data dari mutasi_tiket via relasi
    // =====================================================

    public function getJenisBayarIdAttribute(): ?int
    {
        return $this->mutasiTiket?->jenis_bayar_id;
    }

    public function getNamaPiutangAttribute(): ?string
    {
        return $this->mutasiTiket?->piutang?->nama;
    }

    public function getPembayaranLabelAttribute(): string
    {
        if (!$this->mutasiTiket || !$this->mutasiTiket->jenisBayar) {
            return '-';
        }

        $jenis = strtoupper($this->mutasiTiket->jenisBayar->jenis);

        if ($jenis === 'BANK') {
            return 'BANK ' . ($this->mutasiTiket->bank->name ?? '');
        }

        if ($jenis === 'PIUTANG') {
            return 'PIUTANG - ' . ($this->mutasiTiket->piutang->nama ?? '-');
        }

        return $jenis;
    }
}
