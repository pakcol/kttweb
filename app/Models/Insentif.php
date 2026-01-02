<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insentif extends Model
{
    protected $table = 'insentif';

    protected $fillable = [
        'tgl',
        'jumlah',
        'jenis_tiket_id',
        'jenis_ppob_id',
        'keterangan',
    ];

    public function jenisTiket()
    {
        return $this->belongsTo(JenisTiket::class);
    }

    public function jenisPpob()
    {
        return $this->belongsTo(JenisPpob::class);
    }
}
