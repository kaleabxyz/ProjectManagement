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
        'email' => 'required|string|lowercase|email|max:255|unique:users',
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    DB::beginTransaction(); // Begin transaction

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

        // Create related records
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
            'team_id' => $team->id,
            'member' => $user->id,
            'role' => 'owner',
            'team' => $team->id,
        ]);

        $board = Board::create([
            'board_name' => 'First project',
            'owner' => $user->id,
            'created_by' => $user->id,
            'team' => $team->id,
        ]);
        $team->update(['board' => $board->id]);

        // Attach the board to the workspace using the pivot table
        $workspace->boards()->attach($board->id);

        for ($i = 1; $i <= 4; $i++) {
            Task::create([
                'task_name' => 'Task ' . $i,
                'board_id' => $board->id,
                'assigned_to' => $user->id,
            ]);
        }

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
        $user->workspaces->each(function ($workspace) use ($user) {
            $workspace->boards->each(function ($board) use ($user) {
                // Fetch the role of the user in this board's team
                $teamMember = $user->teamMembers->where('team_id', $board->team_id)->first();
                $userRole = $teamMember ? $teamMember->role : 'Not Assigned';

                // Assign role to discussions
                $board->discussions->each(function ($discussion) use ($userRole) {
                    $discussion->user_role = $userRole;
                });
            });
        });

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
