<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use Inertia\Response;


class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        $tasks = Task::forUser()
            ->withUser()
            ->withComments()
            ->get();
        return Inertia::render('Dashboard/Index', [
            'tasks' => $tasks,
        ]);
    }
}
