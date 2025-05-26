<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'name',
        'text',
        'image',
        'user_id',
        'status',
        'isPinned',
        'forum_id',
    ];
}
