<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teamMembers = TeamMember::with(['user', 'board'])->get();
        return response()->json($teamMembers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'member' => 'required|exists:users,id',
            'board' => 'required|exists:boards,id',
            'role' => 'nullable|string',
        ]);

        $teamMember = TeamMember::create($validatedData);

        return response()->json($teamMember, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TeamMember $teamMember)
    {
        $teamMember->load(['user', 'board']);
        return response()->json($teamMember);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeamMember $teamMember)
    {
        $validatedData = $request->validate([
            'member' => 'nullable|exists:users,id',
            'board' => 'nullable|exists:boards,id',
            'role' => 'nullable|string',
        ]);

        $teamMember->update($validatedData);

        return response()->json($teamMember);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeamMember $teamMember)
    {
        $teamMember->delete();
        return response()->json(['message' => 'Team member deleted successfully']);
    }
}
