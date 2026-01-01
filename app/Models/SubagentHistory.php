<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubagentHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'subagent_id',
        'tiket_kode_booking',
        'saldo'
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
