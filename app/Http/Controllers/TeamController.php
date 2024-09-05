<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    /**
     * Display a listing of the teams.
     */
    public function index(): JsonResponse
    {
        $teams = Team::with(['owner', 'board'])->get();
        return response()->json($teams);
    }

    /**
     * Store a newly created team in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'team_name' => 'required|string|max:255',
            'owner_id' => 'required|exists:users,id',
            'board' => 'required|exists:boards,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $team = Team::create($request->only(['team_name', 'owner_id', 'board']));

        return response()->json($team, 201);
    }

    /**
     * Display the specified team.
     */
    public function show($id): JsonResponse
    {
        $team = Team::with(['owner', 'board'])->findOrFail($id);

        return response()->json($team);
    }

    /**
     * Update the specified team in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $team = Team::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'team_name' => 'sometimes|string|max:255',
            'owner_id' => 'sometimes|exists:users,id',
            'board' => 'sometimes|exists:boards,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $team->update($request->only(['team_name', 'owner_id', 'board']));

        return response()->json($team);
    }

    /**
     * Remove the specified team from storage.
     */
    public function destroy($id): JsonResponse
    {
        $team = Team::findOrFail($id);
        $team->delete();

        return response()->json(null, 204);
    }
}
