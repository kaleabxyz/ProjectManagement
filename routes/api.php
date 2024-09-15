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
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;




// api.php (routes file)

Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'fetchUser']);
Route::middleware('auth:sanctum')->patch('/password', [PasswordController::class, 'update']);
Route::post('/password/reset-link', [PasswordResetLinkController::class, 'store']);

// Route to handle the password reset

Route::post('/register', [RegisteredUserController::class, 'store']);
Route::apiResource('tasks', TaskController::class);
Route::apiResource('boards', BoardController::class);
Route::apiResource('teams', TeamController::class);
Route::apiResource('workspaces', WorkspaceController::class);
Route::apiResource('folders', FolderController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('invitations', InvitationController::class);
Route::apiResource('sub-tasks', SubTaskController::class);
Route::apiResource('updates', UpdateController::class);
Route::apiResource('team-members', TeamMemberController::class);
Route::post('/updates/{id}/read', [UpdateController::class, 'markAsRead']);


Route::post('login', [UserController::class, 'login']);
