<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    /**
     * Display a listing of the workspaces.
     */
    public function index()
    {
        $workspaces = Workspace::with('trashedBy')->get();
        return response()->json($workspaces);
    }

    /**
     * Store a newly created workspace in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'workspace_name' => 'required|string|max:255',
            'is_favorite' => 'boolean',
            'is_archived' => 'boolean',
            'is_trashed' => 'boolean',
            'trashed_at' => 'nullable|date',
            'trashed_by' => 'nullable|exists:users,id',
        ]);

        $workspace = Workspace::create($request->all());

        return response()->json($workspace, 201);
    }

    /**
     * Display the specified workspace.
     */
    public function show($id)
    {
        $workspace = Workspace::with('trashedBy')->findOrFail($id);
        return response()->json($workspace);
    }

    /**
     * Update the specified workspace in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'workspace_name' => 'sometimes|string|max:255',
            'is_favorite' => 'boolean',
            'is_archived' => 'boolean',
            'is_trashed' => 'boolean',
            'trashed_at' => 'nullable|date',
            'trashed_by' => 'nullable|exists:users,id',
        ]);

        $workspace = Workspace::findOrFail($id);
        $workspace->update($request->all());

        return response()->json($workspace);
    }

    /**
     * Remove the specified workspace from storage.
     */
    public function destroy($id)
    {
        $workspace = Workspace::findOrFail($id);
        $workspace->delete();

        return response()->json(null, 204);
    }
}
