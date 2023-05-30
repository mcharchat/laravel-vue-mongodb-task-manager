<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): Response
    {
        // get all projects for the current user and eager load the tasks and user
        $myProjects = Project::where('user_id', auth()->id())
            ->with(['tasks', 'user'])
            ->get();
        // get all projects and eager load the tasks and user
        $projects = Project::all()
            ->load(['tasks', 'user']);
        // return the projects view with the projects
        return Inertia::render('Projects/Index', [
            'myProjects' => $myProjects,
            'projects' => $projects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): Response
    {
        // return the projects create view
        return Inertia::render('Projects/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        //validate the incoming request using our StoreProjectRequest
        $validated = $request->validated();
        // set the user_id to the current user id
        $validated['user_id'] = auth()->id();
        // create a new project with the validated data
        Project::create($validated);
        // return a redirect to the projects index
        return redirect()->route('projects');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project): Response
    {
        // return the projects show view with the project
        return Inertia::render('Projects/Show', [
            'project' => $project,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project): Response
    {
        // return the projects edit view with the project
        return Inertia::render('Projects/Edit', [
            'project' => $project,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        // validate the incoming request using our UpdateProjectRequest
        $validated = $request->validated();
        // update the project with the validated data
        $project->update($validated);
        // return a redirect to the projects index
        return redirect()->route('projects');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project): RedirectResponse
    {
        // delete the project
        $project->delete();
        // return a redirect to the projects index
        return redirect()->route('projects');
    }
}
