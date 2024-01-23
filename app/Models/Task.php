<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;
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

    // global scope to query each task with its squad
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('squad', function (Builder $builder) {
            $builder->where('squad_id', auth()->user()->squad_id);
        });
    }

    // local scope to query each task with its comments
    public function scopeWithComments(Builder $query)
    {
        return $query->with('comments');
    }

    // local scope to query each task with its user
    public function scopeWithUser(Builder $query)
    {
        return $query->with('user');
    }

    // local scope to query each task from the authenticated user
    /**
     * @param Builder $query
     * @param string $type
     * @return Builder
     */
    public function scopeFromUser(Builder $query, string $type)
    {
        if ($type == 'and')
            return $query->where('user_id', auth()->id());
        return $query->orWhere('user_id', auth()->id());
    }

    // local scope to query each task assigned to the authenticated user
    /**
     * @param Builder $query
     * @param string $type
     * @return Builder
     */
    public function scopeAssignedToUser(Builder $query, string $type)
    {
        if ($type == 'and')
            return $query->where('assigned_to', auth()->id());
        return $query->orWhere('assigned_to', auth()->id());
    }

    // local scope to query each task from the authenticated user's team
    /** 
     * @param Builder $query
     * @param string $type
     * @return Builder
     */
    public function scopeForTeam(Builder $query, string $type)
    {
        if ($type == 'and')
            return $query->where('team', 'LIKE', '%' . auth()->id() . '%');
        return $query->orWhere('team', 'LIKE', '%' . auth()->id() . '%');
    }

    // local scope to query each task from the authenticated user or assigned to the authenticated user or from the authenticated user's team
    public function scopeForUser(Builder $query)
    {
        return $query->where(function (Builder $query) {
            $query->fromUser('and')
                ->assignedToUser('or')
                ->forTeam('or');
        });
    }

    // local scope to query public tasks
    public function scopePublic(Builder $query, string $type)
    {
        if ($type == 'and')
            return $query->where('public', true);
        return $query->orWhere('public', true);
    }

    // local scope to query private tasks
    public function scopePrivate(Builder $query, string $type)
    {
        if ($type == 'and')
            return $query->where('public', false);
        return $query->orWhere('public', false);
    }
}
