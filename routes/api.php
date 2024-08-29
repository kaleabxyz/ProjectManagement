<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\SubTaskController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\TeamMemberController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('tasks', TaskController::class);
Route::apiResource('boards', BoardController::class);
Route::apiResource('teams', TeamController::class);
Route::apiResource('workspaces', WorkspaceController::class);
Route::apiResource('folders', FolderController::class);
Route::resource('users', UserController::class);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::apiResource('invitations', InvitationController::class);
Route::apiResource('sub-tasks', SubTaskController::class);
Route::apiResource('updates', UpdateController::class);
Route::apiResource('team-members', TeamMemberController::class);