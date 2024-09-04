<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Workspace;
use App\Models\Board;
use App\Models\Task;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create the new user
        $user = User::create([
            'user_name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create a new workspace for the user
        $workspace = Workspace::create([
            'workspace_name' => 'Main workspace',
            'created_by' => $user->id,
        ]);

        // Create a new board for the workspace
        $board = Board::create([
            'workspace_id' => $workspace->id,
            'board_name' => 'First project',
            'owner' => $user->id,
            'created_by' => $user->id,
        ]);

        // Create 4 tasks for the board
        for ($i = 1; $i <= 4; $i++) {
            Task::create([
                'task_name' => 'Task ' . $i,
                'board_id' => $board->id,
                'assigned_to' => $user->id,
            ]);
        }

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }
}
