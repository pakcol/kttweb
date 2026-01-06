<?php
// app/Models/PembayaranOnline.php

namespace App\Models;

use App\Models\JenisPpob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PembayaranOnline extends Model
{
    protected $table = 'pembayaran_online';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'tgl',
        'id_pel',
        'jenis_ppob_id',
        'nta',
        'harga_jual',
    ];
    
    protected $casts = [
        'tgl' => 'date',
        'nta' => 'integer',
        'harga_jual' => 'integer',
        'komisi' => 'integer',
    ];
    
    protected static function booted()
    {
        static::saving(function ($model) {
            $model->komisi = max(0, $model->harga_jual - $model->nta);
        });
    }

    /**
     * Relasi ke Nota
     */
    public function nota(): HasOne
    {
        return $this->hasOne(Nota::class, 'pembayaran_online_id');
    }

    /**
     * Relasi ke JenisPpob
     */
    public function jenisPpob()
    {
        return $this->belongsTo(JenisPpob::class);
    }

}