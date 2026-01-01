<?php
// app/Models/Subagent.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subagent extends Model
{
    protected $table = 'subagents';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'saldo',
    ];
    
    protected $casts = [
        'saldo' => 'integer',
    ];
    
    public function histories()
    {
        return $this->hasMany(SubagentHistory::class);
    }
}