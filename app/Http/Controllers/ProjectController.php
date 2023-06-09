<?php

namespace App\Http\Controllers;

use App\Events\ProjectEvent;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request): Response
    {
        // get all projects for the current user and eager load the tasks and user
        $myProjects = Project::where('squad_id', auth()->user()->squad_id)
            ->where('user_id', auth()->id())
            ->with(['tasks', 'user'])
            ->get();
        // get all projects and eager load the tasks and user
        $projects = Project::where('squad_id', auth()->user()->squad_id)
            ->with(['tasks', 'user'])
            ->get();
        // return the projects view with the projects
        return Inertia::render('Projects/Index', [
            'myProjects' => $myProjects,
            'projects' => $projects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        //validate the incoming request using our StoreProjectRequest
        $validated = $request->validated();
        // set the user_id to the current user id
        $validated['user_id'] = auth()->id();
        // add the squad_id to the validated data
        $validated['squad_id'] = auth()->user()->squad_id;
        // create a new project with the validated data
        $project = Project::create($validated);
        $project->load('user');
        // fire the ProjectEvent event with the squad_id and the validated data
        event(new ProjectEvent(auth()->user()->squad_id, $project, 'create'));
        // return a redirect to the projects index
        return redirect()->route('projects')->with('success', 'Project created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Inertia\Response
     */
    public function show(Project $project): Response
    {
        // load the tasks and user for the project, with this logic: if the task is public, load for everyone, if not, load only for the current user if he is either the owner of the project or the task is assigned to him, or he is one of the team members
        $project->load(['tasks' => function ($query) {
            $query->where(function ($query) {
                $query->where('public', true)
                ->orWhere('user_id', auth()->id())
                ->orWhere('assigned_to', auth()->id())
                ->orWhere('team', 'LIKE', '%' . auth()->id() . '%');
            });
        }, 'tasks.user', 'tasks.project', 'tasks.comments']);

        // return the projects show view with the project
        return Inertia::render('Projects/Show', [
            'project' => $project,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Inertia\Response
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        // validate the incoming request using our UpdateProjectRequest
        $validated = $request->validated();
        // set the user_id to the current user id
        $validated['user_id'] = auth()->id();
        // update the project with the validated data
        $project->update($validated);
        $project->load('user');
        // fire the ProjectEvent event with the squad_id and the validated data
        event(new ProjectEvent(auth()->user()->squad_id, $project, 'update'));
        // return a redirect to the projects index
        return redirect()->route('projects')->with('success', 'Project updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Project $project): RedirectResponse
    {
        $thisProject = $project->load('user');
        // delete the project
        $project->delete();
        Task::where('project_id', $project->_id)->delete();
        Comment::where('project_id', $project->_id)->delete();
        // fire the ProjectEvent event with the squad_id and the project
        event(new ProjectEvent(auth()->user()->squad_id, $thisProject, 'delete'));
        // return a redirect to the projects index
        return redirect()->route('projects')->with('success', 'Project deleted.');
    }
}
