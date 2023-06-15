<?php

namespace App\Http\Controllers;

use App\Events\PublicTaskEvent;
use App\Events\PrivateTaskEvent;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Redirect;
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
     * @return \Inertia\Response
     */
    public function index(Request $request) : Response
    {
        // get tasks for the current user that don't have aproject_id
        $myTasks = Task::where('squad_id', auth()->user()->squad_id)
            ->where('user_id', auth()->id())
            ->whereNull('project_id')
            ->with('user', 'comments')
            ->get();
        // get tasks for the current user that have a project_id, eager load the project and group them by project
        $myProjectTasks = Task::where('squad_id', auth()->user()->squad_id)
            ->where('user_id', auth()->id())
            ->whereNotNull('project_id')
            ->with('project', 'user', 'comments')
            ->get()
            ->groupBy('project_id');
        // get the assigned tasks for the current user that don't have aproject_id
        $assignedTasks = Task::where('squad_id', auth()->user()->squad_id)
            ->where('assigned_to', auth()->id())
            ->whereNull('project_id')
            ->with('user', 'comments')
            ->get();
        // get the assigned tasks for the current user that have a project_id, eager load the project and group them by project
        $assignedProjectTasks = Task::where('squad_id', auth()->user()->squad_id)
            ->where('assigned_to', auth()->id())
            ->whereNotNull('project_id')
            ->with('project','user', 'comments')
            ->get()
            ->groupBy('project_id');
        // get team tasks for the current user that don't have aproject_id, team is an array of user_ids, so this user_id must be inside this array
        $teamTasks = Task::where('squad_id', auth()->user()->squad_id)
            ->where('team', 'LIKE', '%' . auth()->id() . '%')
            ->whereNull('project_id')
            ->with('user', 'comments')
            ->get();
        // get team tasks for the current user that have a project_id, eager load the project and group them by project, team is an array of user_ids, so this user_id must be inside this array
        $teamProjectTasks = Task::where('squad_id', auth()->user()->squad_id)
            ->where('team', 'LIKE', '%' . auth()->id() . '%')
            ->whereNotNull('project_id')
            ->with('project','user', 'comments')
            ->get()
            ->groupBy('project_id');
        
        // return the tasks view with the tasks
        return Inertia::render('Tasks/Index', [
            'myTasks' => $myTasks,
            'myProjectTasks' => $myProjectTasks,
            'assignedTasks' => $assignedTasks,
            'assignedProjectTasks' => $assignedProjectTasks,
            'teamTasks' => $teamTasks,
            'teamProjectTasks' => $teamProjectTasks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTaskRequest $request) : RedirectResponse
    {
        //validate the incoming request using our StoreTaskRequest
        $validated = $request->validated();
        // set the user_id to the current user id
        $validated['user_id'] = auth()->id();
        // add the squad_id to the validated data
        $validated['squad_id'] = auth()->user()->squad_id;
        // create a new task with the validated data
        Task::create($validated);
        //if task is public, fire the PublicTaskEvent event with the squad_id and the validated data, else merge user_id, assigned_to and squad_id to one array, remove duplicates and null values and fire the PrivateTaskEvent event with the validated data
        if ($validated['public']) {
            event(new PublicTaskEvent(auth()->user()->squad_id, $validated, 'create'));
        } else {
            $participants_ids = collect(array_merge([$validated['user_id']], [$validated['assigned_to'] ?? null], $validated['team'] ?? [null]))
                ->unique()
                ->filter(function ($value) {
                    return !is_null($value);
                });
            event(new PrivateTaskEvent($participants_ids, $validated, 'create'));
        }
        // return a redirect to the last page
        return redirect()->back(303)->with('success', 'Task created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Inertia\Response
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
     * @return \Inertia\Response
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTaskRequest $request, Task $task) : RedirectResponse
    {
        // validate the incoming request using our UpdateTaskRequest
        $validated = $request->validated();
        // update the task with the validated data
        $task->update($validated);
        $updatedTask = Task::find($task->_id);
        //if task is public, fire the PublicTaskEvent event with the squad_id and the validated data, else merge user_id, assigned_to and squad_id to one array, remove duplicates and null values and fire the PrivateTaskEvent event with the validated data
        if ($validated['public']) {
            event(new PublicTaskEvent(auth()->user()->squad_id, $validated, 'update'));
        } else {
            $participants_ids = collect(array_merge([$updatedTask->user_id], [$updatedTask->assigned_to ?? null], $updatedTask->team ?? [null]))
                ->unique()
                ->filter(function ($value) {
                    return !is_null($value);
                });
            event(new PrivateTaskEvent($participants_ids, $updatedTask, 'update'));
        }
        // return a redirect to the last page
        return redirect()->back(303)->with('success', 'Task updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Task $task) : RedirectResponse
    {
        // get the task
        $taskToDelete = Task::find($task->_id)->toArray();
        // delete the task
        $task->delete();
        Comment::where('task_id', $task->_id)->delete();
        //if task is public, fire the PublicTaskEvent event with the squad_id and the validated data, else merge user_id, assigned_to and squad_id to one array, remove duplicates and null values and fire the PrivateTaskEvent event with the validated data
        if ($task['public']) {
            event(new PublicTaskEvent(auth()->user()->squad_id, $taskToDelete, 'delete'));
        } else {
            $participants_ids = collect(array_merge([$taskToDelete['user_id']], [$taskToDelete['assigned_to'] ?? null], $taskToDelete['team'] ?? [null]))
            ->unique()
                ->filter(function ($value) {
                    return !is_null($value);
                });
            event(new PrivateTaskEvent($participants_ids, $taskToDelete, 'delete'));
        }
        // return a redirect to the last page
        return redirect()->back(303)->with('success', 'Task deleted successfully.');
    }

    /**
     * Remove bulk resources from storage.
     *  
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyBulk(Request $request) : RedirectResponse
    {
        // get the ids from the request
        $ids = $request->input('ids');
        // delete the tasks with the ids
        $tasksQuery = Task::whereIn('_id', (array) $ids);
        Comment::whereIn('task_id', (array) $ids)->delete();
        $tasks = $tasksQuery->get();
        foreach ($tasks as $key => $task) {
            if ($task['public']) {
                event(new PublicTaskEvent(auth()->user()->squad_id, $task, 'delete'));
            } else {
                $participants_ids = collect(array_merge([$task['user_id']], [$task['assigned_to'] ?? null], $task['team'] ?? [null]))
                ->unique()
                ->filter(function ($value) {
                    return !is_null($value);
                });
                event(new PrivateTaskEvent($participants_ids, $task, 'delete'));
            }
        }
        $tasksQuery->delete();
        // return a redirect to the last page
        return redirect()->back(303)->with('success', 'Task deleted successfully.');
    }
    
}
