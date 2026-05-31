<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store'])
        ->name('login.store');

    Route::get('/register', [RegisterController::class, 'show'])
        ->name('register');

    Route::post('/register', [RegisterController::class, 'store'])
        ->name('register.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth');

// App
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        $user = auth()->user();
        $team = $user->teams()->first();

        return Inertia::render('Dashboard', [
            'user'  => $user,
            'stats' => [
                'total'       => $team?->tasks()->count() ?? 0,
                'in_progress' => $team?->tasks()->where('status', 'in_progress')->count() ?? 0,
                'done'        => $team?->tasks()->where('status', 'done')->count() ?? 0,
            ],
        ]);
    });

    Route::get('/tasks',           [TaskController::class, 'index']);
    Route::post('/tasks',          [TaskController::class, 'store']);
    Route::patch('/tasks/{task}',  [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
});