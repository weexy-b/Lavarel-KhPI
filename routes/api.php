<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;

// Публічні маршрути
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

//Захищені маршрути (тільки з токеном)
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Проєкти
    Route::get('/projects', [ProjectController::class, 'index']); // Список моїх проєктів
    Route::post('/projects', [ProjectController::class, 'store']); // Створити

    // Група маршрутів для конкретного проєкту (з перевіркою доступу через наш Middleware)
    Route::middleware('project.access')->prefix('projects/{project}')->group(function () {
        Route::get('/', [ProjectController::class, 'show']);
        Route::put('/', [ProjectController::class, 'update']);
        Route::delete('/', [ProjectController::class, 'destroy']);

        // Задачі в межах проєкту
        Route::get('/tasks', [TaskController::class, 'indexByProject']);
        Route::post('/tasks', [TaskController::class, 'store']);
    });

    // Окремі маршрути для Задач (оновлення, видалення, перегляд)
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

    // Коментарі
    Route::get('/tasks/{task}/comments', [CommentController::class, 'index']);
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
});
