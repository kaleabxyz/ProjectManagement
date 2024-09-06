<?php

namespace App\Http\Controllers;

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

    /**
     * Store a newly created board in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'workspace_id' => 'required|exists:workspaces,id',
            'folder_id' => 'nullable|exists:folders,id',
            'board_name' => 'required|string|max:255',
            'owner' => 'required|integer|exists:users,id',
            'created_by' => 'nullable|exists:users,id',
        ]);

        // Create a new team first
        $team = Team::create([
            'team_name' => $request->board_name . " Team",
            'owner_id' => $request->owner,
            'board' => 0, // Temporarily set to 0; it will be updated later
        ]);

        // Create a new board and set the team ID
        $board = Board::create([
            'workspace_id' => $request->workspace_id,
            'board_name' => $request->board_name,
            'owner' => $request->owner,
            'created_by' => $request->created_by ?? $request->owner, // Default to owner if not provided
            'team' => $team->id, // Set the created team ID
        ]);

        // Update the team with the correct board ID
        $team->update(['board' => $board->id]);

        // Add the board creator as the first team member
        TeamMember::create([
            'member' => $request->owner,
            'team' => $team->id,
            'role' => 'Owner', // Or another appropriate role
        ]);

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
