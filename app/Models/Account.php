<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Account extends Authenticatable
{
    use Notifiable;

    // Nama tabel
    protected $table = 'accounts';
    protected $primaryKey = 'id';

    // Field yang bisa diisi
    protected $fillable = [
        'name', 'username', 'role', 'password',
    ];

    // Hidden agar password tidak ikut ke response
    protected $hidden = [
        'password',
    ];

    // Laravel biasanya butuh kolom "remember_token"
    public $timestamps = false; // kalau tabel kamu tidak ada created_at/updated_at
}
