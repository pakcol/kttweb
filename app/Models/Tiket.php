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
        'nta'           => 'integer',
        'harga_jual'    => 'integer',
        'diskon'        => 'integer',
        'nilai_refund'  => 'integer',
        'tgl_realisasi' => 'datetime',
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

    /**
     * Ambil jenis_bayar_id dari mutasi_tiket
     * Dipakai untuk filter tabel di blade (@if $t->jenis_bayar_id == 3)
     */
    public function getJenisBayarIdAttribute(): ?int
    {
        return $this->mutasiTiket?->jenis_bayar_id;
    }

    /**
     * Ambil nama_piutang dari tabel piutang via mutasi_tiket.piutang_id
     * Dipakai untuk kolom "Nama Piutang" di tabel
     */
    public function getNamaPiutangAttribute(): ?string
    {
        return $this->mutasiTiket?->piutang?->nama;
    }

    /**
     * Label pembayaran untuk kolom "Pembayaran" di tabel
     */
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
