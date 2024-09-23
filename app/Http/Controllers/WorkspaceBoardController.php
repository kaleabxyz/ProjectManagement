<?php

namespace App\Http\Controllers;

use App\Models\WorkspaceBoard;
use Illuminate\Http\Request;

class WorkspaceBoardController extends Controller
{
    // Attach a board to a workspace
    public function store(Request $request)
    {
        $request->validate([
            'workspace_id' => 'required|exists:workspaces,id',
            'board_id' => 'required|exists:boards,id',
        ]);

        $workspaceBoard = WorkspaceBoard::create([
            'workspace_id' => $request->workspace_id,
            'board_id' => $request->board_id,
        ]);

        return response()->json($workspaceBoard, 201);
    }

    // Update the workspace of a board
    public function update(Request $request, $id)
    {
        $request->validate([
            'workspace_id' => 'required|exists:workspaces,id',
        ]);

        $workspaceBoard = WorkspaceBoard::find($id);
        
        if (!$workspaceBoard) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $workspaceBoard->workspace_id = $request->workspace_id;
        $workspaceBoard->save();

        return response()->json($workspaceBoard);
    }

    // Delete a board from a workspace
    public function destroy($id)
    {
        $workspaceBoard = WorkspaceBoard::find($id);
        
        if (!$workspaceBoard) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $workspaceBoard->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
