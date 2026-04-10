<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JenisPpob;

class PpobHistory extends Model
{
    protected $table = 'ppob_histories';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tgl',
        'id_pel',
        'jenis_ppob_id',
        'nta',
        'harga_jual',
        'insentif',
        'top_up',
        'komisi',
        'saldo',
        'nama_piutang',
        'jenis_bayar_id',
        'bank_id',
    ];

    protected $casts = [
        'tgl'        => 'date',
        'nta'        => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'insentif'   => 'decimal:2',
        'top_up'     => 'decimal:2',
        'komisi'     => 'decimal:2',
        'saldo'      => 'decimal:2',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            $model->komisi = max(0, ($model->harga_jual ?? 0) - ($model->nta ?? 0));
        });
    }

    public function jenisPpob()
    {
        return $this->belongsTo(JenisPpob::class, 'jenis_ppob_id');
    }

    public function jenisBayar()
    {
        return $this->belongsTo(JenisBayar::class, 'jenis_bayar_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
}
