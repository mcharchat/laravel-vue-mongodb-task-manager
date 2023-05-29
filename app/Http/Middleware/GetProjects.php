<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
// import the Project model
use App\Models\Project;
use Inertia\Inertia;


class GetProjects
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
        // get all projects, ordered by updatedate, limit by 10 and eager load the tasks and user
        $projects = Project::orderBy('updated_at', 'desc')->limit(10)->with('user')->get();
        
        // share the projects with the view in Inertia
        Inertia::share([
            'projects' => $projects,
        ]);
        return $next($request);
    }
}
