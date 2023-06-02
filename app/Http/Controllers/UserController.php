<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        // get all users and eager load the tasks and user
        $users = User::all()
            ->load(['tasks']);
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
        // return the users show view with the user
        return Inertia::render('Users/Show', [
            'user' => $user,
        ]);
    }
}
