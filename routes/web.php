<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use Illuminate\Foundation\Application;
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

Route::middleware(['auth', 'verified', 'get-projects'])->group(function () {
    // route for the dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

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

    // route for showing the profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // route for updating the profile
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // route for deleting the profile
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
