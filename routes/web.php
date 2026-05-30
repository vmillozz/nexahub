<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return Inertia::render('Dashboard', [
        'user'  => ['name' => 'Vito'],
        'stats' => [
            'total'       => 20,
            'in_progress' => 5,
            'done'        => 8,
        ],
    ]);
});
Route::get('/tasks',          [TaskController::class, 'index']);
Route::post('/tasks',         [TaskController::class, 'store']);
Route::patch('/tasks/{task}', [TaskController::class, 'update']);
Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);