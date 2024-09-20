<?php

namespace App\Http\Controllers;
use App\Events\BoardCreated; // Import event class
use App\Models\User;
use App\Models\Team;
use App\Models\Board;
use App\Models\Workspace;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BoardController extends Controller
{
    /**
     * Display a listing of the boards.
     */
    public function index()
{
    // Fetch boards with related data including the pivot relationships
    $boards = Board::with([
        'workspaces' => function ($query) {
            $query->withPivot('created_at','workspace_id', 'board_id',); // If you need any pivot data like 'created_at'
        },
        'owner:id,user_name,email,profile_picture_url',
        'creator:id,user_name,email,profile_picture_url',
        'team',
        'team.members',
        'tasks' => function ($query) {
            $query->withCount('updates');
        },
        'tasks.SubTasks',
        'tasks.assignedUser:id,user_name,email,profile_picture_url',
        'discussions' => function ($query) {
            $query->with(['user:id,user_name,email,profile_picture_url', 'task:id,task_name']);
        }
    ])->get();

    return response()->json($boards);
}

    public function create()
    {
        // You may not need this for an API. For web apps, return a view.
    }
    /**
     * Store a newly created board in storage.
     */
    
    
     public function store(Request $request)
     {
         // Validate incoming request data
         $validatedData = $request->validate([
             'workspace_id' => 'required|exists:workspaces,id',
             'folder_id' => 'nullable|exists:folders,id',
             'board_name' => 'required|string|max:255',
             'owner' => 'required|integer|exists:users,id',
             'created_by' => 'nullable|exists:users,id',
             'team' => 'nullable|exists:teams,id',
         ]);
     
         // Log the validated data
         Log::info('Validated Board Data:', $validatedData);
     
         // Create a new board
         $board = Board::create([
             'board_name' => $request->board_name,
             'owner' => $request->owner,
             'created_by' => $request->created_by ?? $request->owner, // Default to owner if not provided
         ]);
     
         // Attach the board to the workspace using the pivot table
         $workspace = Workspace::find($request->workspace_id);
         $workspace->boards()->attach($board->id);
     
         // Create a new team and associate it with the created board
         $team = Team::create([
             'team_name' => $request->board_name . " Team",
             'owner_id' => $request->owner,
             'board' => $board->id, // Use the newly created board's ID
         ]);
     
         // Update the board with the correct team ID
         $board->update(['team' => $team->id]);
     
         // Add the board creator as the first team member (Owner)
         TeamMember::create([
             'member' => $request->owner,
             'team' => $team->id,
             'role' => 'Owner',
         ]);
     
         // Step 1: Find the user with the Manager role
         $manager = User::where('role', 'Manager')->first();
         
         if ($manager) {
             // Step 2: Add the manager as a team member with 'Viewer' role
             TeamMember::create([
                 'member' => $manager->id,
                 'team' => $team->id,
                 'role' => 'Viewer', // Read-only
             ]);
     
             // Step 3: Add the board to the manager's main workspace
             $mainWorkspace = $manager->workspaces()->orderBy('created_at')->first();
             if ($mainWorkspace) {
                 // Attach the board to the main workspace
                 $mainWorkspace->boards()->attach($board->id);
             }
         }
     
         // Fetch the newly created board with all relationships
         $board = Board::with([
             'workspaces' => function ($query) {
                 $query->withPivot('created_at', 'workspace_id', 'board_id');
             },
             'owner:id,user_name,email,profile_picture_url',
             'creator:id,user_name,email,profile_picture_url',
             'team',
             'team.members',
             'tasks' => function ($query) {
                 $query->withCount('updates');
             },
             'tasks.SubTasks',
             'tasks.assignedUser:id,user_name,email,profile_picture_url',
             'discussions' => function ($query) {
                 $query->with(['user:id,user_name,email,profile_picture_url', 'task:id,task_name']);
             }
         ])->find($board->id);
     
         // Dispatch the event with the loaded board data
         BoardCreated::dispatch($board->id, $manager);
         
         // Log success data
         Log::info('Board and Team created successfully:', ['board' => $board, 'team' => $team]);
         
         return response()->json(['board' => $board, 'team' => $team], 201);
     }
     
    
    /**
     * Display the specified board.
     */
    public function show($id, Request $request)
{
    // Get the workspace_id from the request
    $workspaceId = $request->query('workspace_id');

    $board = Board::with([
        'workspaces' => function ($query) {
            $query->withPivot('workspace_id', 'board_id', 'created_at', 'updated_at');
        },
        'owner:id,user_name,email,profile_picture_url',
        'creator:id,user_name,email,profile_picture_url',
        'team',
        'team.members',
        'tasks' => function ($query) {
            $query->withCount('updates');
        },
        'tasks.SubTasks',
        'tasks.assignedUser:id,user_name,email,profile_picture_url',
        'discussions' => function ($query) {
            $query->with(['user:id,user_name,email,profile_picture_url', 'task:id,task_name']);
        }
    ])->findOrFail($id);

    // Extract pivot data based on the specified workspace_id
    $matchingWorkspace = $board->workspaces->first(function ($workspace) use ($workspaceId) {
        return $workspace->pivot->workspace_id == $workspaceId;
    });

    if ($matchingWorkspace) {
        $board->pivot = [
            'workspace_id' => $matchingWorkspace->pivot->workspace_id,
            'board_id' => $matchingWorkspace->pivot->board_id,
            'created_at' => $matchingWorkspace->pivot->created_at,
            'updated_at' => $matchingWorkspace->pivot->updated_at,
        ];
    } else {
        // Optionally handle the case where no matching workspace is found
        $board->pivot = null; // or return an error response
    }

    // Optionally, remove the workspaces relationship if not needed anymore
    unset($board->workspaces);

    return response()->json($board);
}


    

    /**
     * Update the specified board in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'workspace_id' => 'sometimes|exists:workspaces,id',
            'folder_id' => 'sometimes|exists:folders,id',
            'team' => 'sometimes|exists:teams,id',
            'board_name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);

        $board = Board::findOrFail($id);
        $board->update($request->all());

        return response()->json($board);
    }

    /**
     * Remove the specified board from storage.
     */
    public function destroy($id)
    {
        $board = Board::findOrFail($id);
        $board->delete();

        return response()->json(null, 204);
    }
}
