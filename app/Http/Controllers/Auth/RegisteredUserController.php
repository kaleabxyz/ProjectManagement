<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workspace;
use App\Models\Board;
use App\Models\Task;
use Illuminate\Auth\Events\Registered;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

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

        // Return a JSON response to handle with Vue
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
        
    
    }
}
