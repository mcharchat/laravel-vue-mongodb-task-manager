<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;


class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() : Response
    {
        $tasks = Task::where('squad_id', auth()->user()->squad_id)
        // where function
            ->where(function ($query) {
                // where function
                $query->Where('user_id', auth()->id())
                    // orWhere function
                    ->orWhere('assigned_to', auth()->id())
                    // orWhere function
                    ->orWhere('team', 'LIKE', '%' . auth()->id() . '%');
            })
            ->with('user', 'comments')
            ->get();
        return Inertia::render('Dashboard/Index', [
            'tasks' => $tasks,
        ]);
    }
}
