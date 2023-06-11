<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    // set the fillable fields for the model
    protected $fillable = [
        'name',
        'description',
        'user_id',
        'squad_id',
    ];
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
