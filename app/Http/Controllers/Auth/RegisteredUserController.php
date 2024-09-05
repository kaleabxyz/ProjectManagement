<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workspace;
use App\Models\Board;
use App\Models\Task;
use App\Models\Team; // Import the Team model
use App\Models\TeamMember; // Import the TeamMember model
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        // You might want to return a view here if using a traditional web approach.
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
       
        try {
            // Create the user
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

            // Create a new team with the registering user as the owner
            $team = Team::create([
                'team_name' => 'First project team',
                'owner_id' => $user->id,
                'board' => null, // Placeholder for board_id
            ]);

            // Create a team member instance for the registering user
            TeamMember::create([
                'team' => $team->id,
                'member' => $user->id,
                'role' => 'owner',
            ]);

            // Create a new board for the workspace and assign the team_id
            $board = Board::create([
                'workspace_id' => $workspace->id,
                'board_name' => 'First project',
                'owner' => $user->id,
                'created_by' => $user->id,
                'team' => $team->id, // Assign the team_id here
            ]);
            $team->update(['board' => $board->id]);

            // Create 4 sample tasks for the board
            for ($i = 1; $i <= 4; $i++) {
                Task::create([
                    'task_name' => 'Task ' . $i,
                    'board_id' => $board->id,
                    'assigned_to' => $user->id,
                ]);
            }

            DB::commit(); // Commit transaction

            // Return a JSON response to handle with Vue
            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user
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
