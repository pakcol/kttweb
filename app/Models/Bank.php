<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'bank';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'id_tiket',      // ID tiket terkait
        'nama_bank',     // Nama bank (BCA, BTN, BNI, MANDIRI, BRI)
        'total_harga'    // Total harga setelah dikurangi NTA
    ];

    // Relasi ke tiket
    public function tiket()
    {
        return $this->belongsTo(Tiket::class, 'id_tiket');
    }
}
