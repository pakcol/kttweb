<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubagentHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'tgl_issued',
        'subagent_id',
        'kode_booking',
        'status',
        'transaksi'
    ];

    protected $casts = [
        'transaksi' => 'decimal:2',
    ];

    public function subagent()
    {
        return $this->belongsTo(Subagent::class);
    }

    public function tiket()
    {
        return $this->belongsTo(Tiket::class, 'kode_booking', 'kode_booking');
    }
}
