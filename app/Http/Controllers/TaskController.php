<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) : Response
    {
        // get tasks for the current user that don't have aproject_id
        $myTasks = Task::where('user_id', auth()->id())
            ->with('user')
            ->whereNull('project_id')
            ->get();
        // get tasks for the current user that have a project_id, eager load the project and group them by project
        $myProjectTasks = Task::where('user_id', auth()->id())
            ->whereNotNull('project_id')
            ->with('project', 'user')
            ->get()
            ->groupBy('project_id');
        // get the assigned tasks for the current user that don't have aproject_id
        $assignedTasks = Task::where('assigned_to', auth()->id())
            ->whereNull('project_id')
            ->with('user')
            ->get();
        // get the assigned tasks for the current user that have a project_id, eager load the project and group them by project
        $assignedProjectTasks = Task::where('assigned_to', auth()->id())
            ->whereNotNull('project_id')
            ->with('project', 'user')
            ->get()
            ->groupBy('project_id');
        // get team tasks for the current user that don't have aproject_id, team is an array of user_ids, so this user_id must be inside this array
        $teamTasks = Task::where('team', 'LIKE', '%' . auth()->id() . '%')
            ->whereNull('project_id')
            ->with('user')
            ->get();
        // get team tasks for the current user that have a project_id, eager load the project and group them by project, team is an array of user_ids, so this user_id must be inside this array
        $teamProjectTasks = Task::where('team', 'LIKE', '%' . auth()->id() . '%')
            ->whereNotNull('project_id')
            ->with('project', 'user')
            ->get()
            ->groupBy('project_id');
        // get all the users details grouped by _id
        $users = User::all()->keyBy('_id');
        
        // return the tasks view with the tasks
        return Inertia::render('Tasks/Index', [
            'myTasks' => $myTasks,
            'myProjectTasks' => $myProjectTasks,
            'assignedTasks' => $assignedTasks,
            'assignedProjectTasks' => $assignedProjectTasks,
            'teamTasks' => $teamTasks,
            'teamProjectTasks' => $teamProjectTasks,
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() : Response
    {
        // return the tasks create view
        return Inertia::render('Tasks/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTaskRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskRequest $request) : RedirectResponse
    {
        //validate the incoming request using our StoreTaskRequest
        $validated = $request->validated();
        // create a new task with the validated data
        Task::create($validated);
        // return a redirect to the tasks index
        return redirect()->route('tasks');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task) : Response
    {
        // return the tasks show view
        return Inertia::render('Tasks/Show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task) : Response
    {
        // return the tasks edit view
        return Inertia::render('Tasks/Edit', [
            'task' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTaskRequest  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskRequest $request, Task $task) : RedirectResponse
    {
        // validate the incoming request using our UpdateTaskRequest
        $validated = $request->validated();
        // update the task with the validated data
        $task->update($validated);
        // return a redirect to the tasks index
        return redirect()->route('tasks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task) : RedirectResponse
    {
        // delete the task
        $task->delete();
        // return a redirect to the tasks index
        return redirect()->route('tasks');
    }
}
