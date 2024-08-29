<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    /**
     * Display a listing of the folders.
     */
    public function index()
    {
        $folders = Folder::with(['team', 'workspace', 'trashedBy'])->get();
        return response()->json($folders);
    }

    /**
     * Store a newly created folder in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'team_id' => 'required|exists:teams,id',
            'workspace_id' => 'required|exists:workspaces,id',
            'is_favorite' => 'boolean',
            'is_archived' => 'boolean',
            'is_trashed' => 'boolean',
            'trashed_at' => 'nullable|date',
            'trashed_by' => 'nullable|exists:users,id',
        ]);

        $folder = Folder::create($request->all());

        return response()->json($folder, 201);
    }

    /**
     * Display the specified folder.
     */
    public function show($id)
    {
        $folder = Folder::with(['team', 'workspace', 'trashedBy'])->findOrFail($id);
        return response()->json($folder);
    }

    /**
     * Update the specified folder in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'folder_name' => 'sometimes|string|max:255',
            'team_id' => 'sometimes|exists:teams,id',
            'workspace_id' => 'sometimes|exists:workspaces,id',
            'is_favorite' => 'boolean',
            'is_archived' => 'boolean',
            'is_trashed' => 'boolean',
            'trashed_at' => 'nullable|date',
            'trashed_by' => 'nullable|exists:users,id',
        ]);

        $folder = Folder::findOrFail($id);
        $folder->update($request->all());

        return response()->json($folder);
    }

    /**
     * Remove the specified folder from storage.
     */
    public function destroy($id)
    {
        $folder = Folder::findOrFail($id);
        $folder->delete();

        return response()->json(null, 204);
    }
}
