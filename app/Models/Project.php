<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
    // every project has many tasks
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    // every project has one user that created it
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
