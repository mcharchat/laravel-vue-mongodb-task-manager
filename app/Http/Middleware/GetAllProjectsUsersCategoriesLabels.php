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
        $allProjects = Project::orderBy('name', 'asc')
            ->with('user')
            ->get();
        $allUsers = User::orderBy('name', 'asc')
            ->get();

        $allCategories = Task::all()->pluck('category')->flatten()->unique();
        $allLabels = Task::all()->pluck('labels')->flatten()->unique();
        $allLabels = $allLabels->reject(function ($value, $key) {
            return $value === null;
        });
        
        Inertia::share([
            'allProjects' => $allProjects,
            'allUsers' => $allUsers,
            'allCategories' => $allCategories,
            'allLabels' => $allLabels,
        ]);
        return $next($request);
    }
}
