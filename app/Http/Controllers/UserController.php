<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): Response
    {
        // get all users that are not the current user and eager load the tasks
        $users = User::where('_id', '!=', auth()->id())
            ->with('tasks')
            ->get();
        // return the users view with the users
        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user): Response
    {
        // all the tasks below have to obey one the following rules in order to be displayed to the current user:
        // 1. the task has to be assigned to the current user
        // 2. the task has to be owned by the current user
        // 3. the task has to be assigned to a team that the current user is a member of
        // get tasks for the selected user that don't have a project_id
        $myTasks = Task::where('user_id', $user->_id)
            ->whereNull('project_id')
            ->where(function ($query) {
                $query->where('assigned_to', auth()->id())
                    ->orWhere('team', 'LIKE', '%' . auth()->id() . '%');
            })
            ->with('user')
            ->get();
        // get tasks for the selected user that have a project_id, eager load the project and group them by project
        $myProjectTasks = Task::where('user_id', $user->_id)
            ->whereNotNull('project_id')
            ->where(function ($query) {
                $query->where('assigned_to', auth()->id())
                    ->orWhere('team', 'LIKE', '%' . auth()->id() . '%');
            })
            ->with('project', 'user')
            ->get()
            ->groupBy('project_id');
        // get the assigned tasks for the selected user that don't have a project_id
        $assignedTasks = Task::where('assigned_to', $user->_id)
            ->whereNull('project_id')
            ->where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('team', 'LIKE', '%' . auth()->id() . '%');
            })
            ->with('user')
            ->get();
        // get the assigned tasks for the selected user that have a project_id, eager load the project and group them by project
        $assignedProjectTasks = Task::where('assigned_to', $user->_id)
            ->whereNotNull('project_id')
            ->where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('team', 'LIKE', '%' . auth()->id() . '%');
            })
            ->with('project', 'user')
            ->get()
            ->groupBy('project_id');
        // get team tasks for the selected user that don't have aproject_id, team is an array of user_ids, so this user_id must be inside this array
        $teamTasks = Task::where('team', 'LIKE', '%' . auth()->id() . '%')
            ->whereNull('project_id')
            ->where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('assigned_to', auth()->id());
            })
            ->with('user')
            ->get();
        // get team tasks for the selected user that have a project_id, eager load the project and group them by project, team is an array of user_ids, so this user_id must be inside this array
        $teamProjectTasks = Task::where('team', 'LIKE', '%' . auth()->id() . '%')
            ->whereNotNull('project_id')
            ->where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('assigned_to', auth()->id());
            })
            ->with('project', 'user')
            ->get()
            ->groupBy('project_id');
        // get all the users details grouped by _id
        $users = User::all()->keyBy('_id');        

        // return the users show view with the user
        return Inertia::render('Users/Show', [
            'user' => $user,
            'myTasks' => $myTasks,
            'myProjectTasks' => $myProjectTasks,
            'assignedTasks' => $assignedTasks,
            'assignedProjectTasks' => $assignedProjectTasks,
            'teamTasks' => $teamTasks,
            'teamProjectTasks' => $teamProjectTasks,
            'users' => $users,
        ]);
    }
}
