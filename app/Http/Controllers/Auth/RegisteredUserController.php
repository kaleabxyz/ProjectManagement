<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::beginTransaction(); // Begin transaction

        try {
            $isFirstUser = User::count() == 0;
            // Create the user
            $user = User::create([
                'user_name' => $request->user_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $isFirstUser ? 'Manager' : 'User',  // Set role based on if user is first
            ]);

            // Log the user in and generate a token
            $token = Auth::attempt($request->only('email', 'password'));

            if (!$token) {
                return response()->json(['error' => 'Could not generate token'], 500);
            }

            // Create the main workspace
            $workspace = Workspace::create([
                'workspace_name' => 'Main workspace',
                'created_by' => $user->id,
            ]);

            DB::commit(); // Commit transaction

        // Load the user with updated relationships
        $user = Auth::user()->load([
            'workspaces' => function ($query) {
                $query->with([
                    'boards' => function ($query) {
                        $query->with([
                            'owner:id,user_name,email,profile_picture_url',
                            'creator:id,user_name,email,profile_picture_url',
                            'team',
                            'team.members',
                            'tasks' => function ($query) {
                                $query->withCount('updates'); // Include update counts
                            },
                            'tasks.SubTasks',
                            'tasks.assignedUser:id,user_name,email,profile_picture_url',
                            'discussions' => function ($query) {
                                $query->with([
                                    'user:id,user_name,email,profile_picture_url',
                                    'task:id,task_name'
                                ]);
                            }
                        ]);
                    }
                ]);
            },
            'teamMembers'
        ]);

        // Assign role to discussions
        

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 201);
    } catch (\Exception $e) {
        DB::rollBack(); // Rollback transaction on failure

        return response()->json([
            'message' => 'Failed to register user',
            'error' => $e->getMessage()
        ], 500);
    }
}


}
