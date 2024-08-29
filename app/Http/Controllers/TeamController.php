<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the teams.
     */
    public function index()
    {
        $teams = Team::with('owner')->get();
        return response()->json($teams);
    }

    /**
     * Store a newly created team in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'team_name' => 'required|string|max:255',
            'owner_id' => 'required|exists:users,id',
        ]);

        $team = Team::create($request->all());

        return response()->json($team, 201);
    }

    /**
     * Display the specified team.
     */
    public function show($id)
    {
        $team = Team::with('owner')->findOrFail($id);
        return response()->json($team);
    }

    /**
     * Update the specified team in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'team_name' => 'sometimes|string|max:255',
            'owner_id' => 'sometimes|exists:users,id',
        ]);

        $team = Team::findOrFail($id);
        $team->update($request->all());

        return response()->json($team);
    }

    /**
     * Remove the specified team from storage.
     */
    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        $team->delete();

        return response()->json(null, 204);
    }
}
