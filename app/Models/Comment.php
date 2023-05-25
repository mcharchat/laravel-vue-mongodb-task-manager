<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    // every comment belongs to one user
    function user()
    {
        return $this->belongsTo(User::class);
    }
    // every comment belongs to one task
    function task()
    {
        return $this->belongsTo(Task::class);
    }
}
