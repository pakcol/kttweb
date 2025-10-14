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
    protected $primaryKey = 'id';  
    protected $fillable = [
        'name', 'username', 'role', 'password',
    ];    
    protected $hidden = [
        'password',
    ];
    public $timestamps = false; 
}
