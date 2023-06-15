<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
// import the Project model
use App\Models\Project;
use App\Models\Task;
use Inertia\Inertia;


class GetAllProjectsUsersCategoriesLabels
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $allProjects = Project::where('squad_id', auth()->user()->squad_id)
            ->orderBy('name', 'asc')
            ->with('user')
            ->withTrashed()
            ->get();
        $allUsers = User::where('squad_id', auth()->user()->squad_id)
            ->orderBy('name', 'asc')
            ->withTrashed()
            ->get();

        $allCategories = Task::where('squad_id', auth()->user()->squad_id)
            ->get()
            ->pluck('category')
            ->flatten()
            ->unique()
            ->reject(function ($value, $key) {
                return $value === null;
            })->values();
        $allLabels = Task::where('squad_id', auth()->user()->squad_id)
            ->get()
            ->pluck('labels')
            ->flatten()
            ->unique()
            ->reject(function ($value, $key) {
                return $value === null;
            })->values();
        
        Inertia::share([
            'allProjects' => $allProjects,
            'allUsers' => $allUsers,
            'allCategories' => $allCategories,
            'allLabels' => $allLabels,
        ]);
        return $next($request);
    }
}
