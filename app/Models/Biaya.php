<?php
// app/Models/Biaya.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Biaya extends Model
{
    protected $table = 'biaya';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'tgl',
        'biaya',
        'kategori',
        'jenis_bayar_id',
        'bank_id',
        'keterangan'
    ];
    
    protected $casts = [
        'tgl' => 'datetime',
        'biaya' => 'integer',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * Relasi ke JenisBayar
     */
    public function jenisBayar(): BelongsTo
    {
        return $this->belongsTo(JenisBayar::class, 'jenis_bayar_id');
    }

    public function jenisTiket()
    {
        return $this->belongsTo(JenisTiket::class, 'id_jenis_tiket');
    }

}