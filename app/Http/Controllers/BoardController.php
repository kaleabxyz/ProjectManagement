<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use App\Models\Board;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Display a listing of the boards.
     */
    public function index()
    {
        $boards = Board::all();
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
        'workspace_id' => $request->workspace_id,
        'board_name' => $request->board_name,
        'owner' => $request->owner,
        'created_by' => $request->created_by ?? $request->owner, // Default to owner if not provided
        // Assuming the team ID is added later
    ]);

    // Create a new team and associate it with the created board
    $team = Team::create([
        'team_name' => $request->board_name . " Team",
        'owner_id' => $request->owner,
        'board' => $board->id, // Use the newly created board's ID
    ]);

    // Update the board with the correct team ID (if necessary)
    $board->update(['team' => $team->id]); // This assumes the board has a 'team' column to reference the team ID

    // Add the board creator as the first team member
    TeamMember::create([
        'member' => $request->owner,
        'team' => $team->id,
        'role' => 'Owner', // Or another appropriate role
    ]);
    
    // Log success data
    Log::info('Board and Team created successfully:', ['board' => $board, 'team' => $team]);
    
    return response()->json(['board' => $board, 'team' => $team], 201);
}

    /**
     * Display the specified board.
     */
    public function show($id)
    {
        $board = Board::with(['owner', 'createdBy', 'workspace', 'folder', 'team', 'trashedBy'])->findOrFail($id);
        return response($board);
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
