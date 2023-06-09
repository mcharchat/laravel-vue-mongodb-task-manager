<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified', 'get-top-projects-users', 'get-all-projects-users-categories-labels'])->group(function () {
    // route for the dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // route for listing projects
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects');
    // route for creating a project
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    // route for storing a project
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    // route for showing a project
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    // route for editing a project
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    // route for updating a project
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    // route for deleting a project
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // route for listing tasks
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
    // route for creating a task
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    // route for storing a task
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    // route for showing a task
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    // route for editing a task
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    // route for updating a task
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    // route for deleting a task
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    // route for deleting bulk tasks
    Route::delete('/tasks', [TaskController::class, 'destroyBulk'])->name('tasks.destroy.bulk');

    // route for listing users
    Route::get('/users', [UserController::class, 'index'])->name('users');
    // route for creating a user
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    // route for showing a user
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    // route for storing a user
    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    // route for showing search results
    Route::get('/search', [SearchController::class, 'show'])->name('search.show');

    // route for showing the profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // route for updating the profile
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // route for deleting the profile
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // route for updating the starred projects
    Route::put('/profile/starred-projects', [ProfileController::class, 'updateStarredProjects'])->name('profile.starred-projects.update');
    // route for updating the starred users
    Route::put('/profile/starred-users', [ProfileController::class, 'updateStarredUsers'])->name('profile.starred-users.update');

    // route for posting a comment
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
});

require __DIR__ . '/auth.php';
