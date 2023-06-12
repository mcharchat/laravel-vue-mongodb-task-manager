<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
// import the Project model
use App\Models\Project;
use Inertia\Inertia;


class GetTopProjectsUsers
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
        if (!$starredProjects) {
            $topProjects = [];
        } else {
            // get those projects from the database
        $topProjects = Project::whereIn('_id', $starredProjects)
            ->orderBy('updated_at', 'desc')
            ->with('user')
            ->get();
        }
        // get the user starred users array
        $starredUsers = $request->user()->starred_users;
        if (!$starredUsers) {
            $topUsers = [];
        } else {
            // get those users from the database
            $topUsers = User::whereIn('_id', $starredUsers)
                ->orderBy('updated_at', 'desc')
                ->get();
        }

        $allProjects = Project::orderBy('name', 'asc')
            ->with('user')
            ->get();
        $allUsers = User::orderBy('name', 'asc')
            ->get();
        
        // share the projects with the view in Inertia
        Inertia::share([
            'topProjects' => $topProjects,
            'topUsers' => $topUsers,
            'allProjects' => $allProjects,
            'allUsers' => $allUsers,
        ]);
        return $next($request);
    }
}
