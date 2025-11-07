<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Account extends Authenticatable
{
    use Notifiable;
    
    protected $table = 'accounts';
    protected $primaryKey = 'username';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'username', 'name', 'password', 'roles',
    ];    
    protected $hidden = [
        'password',
    ];
    public $timestamps = true;
}
