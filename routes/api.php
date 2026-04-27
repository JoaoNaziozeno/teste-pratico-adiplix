<?php

use App\Http\Controllers\PersonController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::apiResource('people', PersonController::class);
Route::apiResource('tasks', TaskController::class);

Route::post('persons/{person}/tasks/{task}', [PersonController::class, 'attachTask']);
Route::delete('persons/{person}/tasks/{task}', [PersonController::class, 'detachTask']);

Route::post('tasks/{task}/people/{person}', [TaskController::class, 'attachPerson']);
Route::delete('tasks/{task}/people/{person}', [TaskController::class, 'detachPerson']);
Route::post('people/{person}/tasks/{task}', [PersonController::class, 'attachTask']);
Route::delete('people/{person}/tasks/{task}', [PersonController::class, 'detachTask']);