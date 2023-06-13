<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
    // every task belongs to one user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // every task has many comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    // every task may belong to one project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
