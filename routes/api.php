<?php

use App\Http\Controllers\PersonController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::apiResource('people', PersonController::class);
Route::apiResource('tasks', TaskController::class);

Route::get('people/{person}/tasks', [PersonController::class, 'listTasks']);
Route::post('people/{person}/tasks', [PersonController::class, 'attachTask']);
Route::delete('people/{person}/tasks', [PersonController::class, 'detachTask']);

Route::get('tasks/{task}/people', [TaskController::class, 'listPeople']);
Route::post('tasks/{task}/people', [TaskController::class, 'attachPeople']);
Route::delete('tasks/{task}/people', [TaskController::class, 'detachPeople']);