<?php

namespace App\Http\Controllers;

use App\Models\Board;
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
        $request->validate([
            'workspace_id' => 'required|exists:workspaces,id',
            'folder_id' => 'required|exists:folders,id',
            'team' => 'required|exists:teams,id',
            'board_name' => 'required|string|max:255',
            'owner' => 'required|integer|exists:users,id',
        ]);

        $board = Board::create($request->all());

        return response()->json($board, 201);
    }

    /**
     * Display the specified board.
     */
    public function show($id)
    {
        $board = Board::findOrFail($id);
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
