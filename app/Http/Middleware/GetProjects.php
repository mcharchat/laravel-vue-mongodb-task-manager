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
        // get the user starred projects array
        $starredProjects = $request->user()->starred_projects;
        // get those projects from the database
        $topProjects = Project::whereIn('_id', $starredProjects)
            ->orderBy('updated_at', 'desc')
            ->with('user')
            ->get();
        
        // share the projects with the view in Inertia
        Inertia::share([
            'topProjects' => $topProjects,
        ]);
        return $next($request);
    }
}
