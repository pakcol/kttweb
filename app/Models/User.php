<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users'; // ganti dari accounts ke users
    protected $primaryKey = 'id';

    protected $fillable = [
        'username',
        'name',
        'password',
        'roles'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAuthIdentifierName()
    {
        return 'username';
    }
}
