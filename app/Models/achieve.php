<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class achieve extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'achievement_id',
    ];
}
