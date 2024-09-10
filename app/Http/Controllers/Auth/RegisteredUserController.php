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

        // Log the user in and generate a token
        $token = Auth::attempt($request->only('email', 'password'));

        if (!$token) {
            return response()->json(['error' => 'Could not generate token'], 500);
        }

        // Create related records as before
        $workspace = Workspace::create([
            'workspace_name' => 'Main workspace',
            'created_by' => $user->id,
        ]);

        $team = Team::create([
            'team_name' => 'First project team',
            'owner_id' => $user->id,
            'board' => null, // Placeholder for board_id
        ]);

        TeamMember::create([
            'team' => $team->id,
            'member' => $user->id,
            'role' => 'owner',
        ]);

        $board = Board::create([
            'workspace_id' => $workspace->id,
            'board_name' => 'First project',
            'owner' => $user->id,
            'created_by' => $user->id,
            'team' => $team->id,
        ]);
        $team->update(['board' => $board->id]);

        for ($i = 1; $i <= 4; $i++) {
            Task::create([
                'task_name' => 'Task ' . $i,
                'board_id' => $board->id,
                'assigned_to' => $user->id,
            ]);
        }

        DB::commit(); // Commit transaction
        $user = Auth::user()->load([
            'workspaces',
            'workspaces.folders',
            'workspaces.boards',
            'workspaces.boards.owner:id,user_name,email,profile_picture_url',
            'workspaces.boards.creator:id,user_name,email,profile_picture_url',
            'workspaces.boards.team',
            'workspaces.boards.team.members',
            'workspaces.boards.tasks',
            'workspaces.boards.tasks.SubTasks',
            'workspaces.boards.tasks.assignedUser:id,user_name,email,profile_picture_url',
            'workspaces.boards.discussions.board:id,board_name',    
            'workspaces.boards.discussions',  
            'workspaces.boards.discussions.task:id,task_name', 

            'workspaces.boards.discussions.user:id,user_name,email,profile_picture_url',    


        ]);
        // Return the token and user data
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
