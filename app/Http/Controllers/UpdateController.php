<?php

namespace App\Http\Controllers;

use App\Models\Update;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $updates = Update::with('user', 'task', 'board')->get();
        return response()->json($updates);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'task_id' => 'nullable|exists:tasks,id',
                'user_id' => 'nullable|exists:users,id',
                'content' => 'required|string',
                'read' => 'nullable|boolean',
                'board_id' => 'required|exists:boards,id',
            ]);
    
            $update = Update::create($validatedData);
    
            return response()->json($update, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors to the Laravel log
            Log::error('Validation Error:', $e->errors());
    
            // Return validation errors to the client
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Update $update)
    {
        return response()->json($update);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Update $update)
    {
        $validatedData = $request->validate([
            'task_id' => 'nullable|exists:tasks,id',
            'user_id' => 'nullable|exists:users,id',
            'content' => 'required|string',
            'read' => 'nullable|boolean',
            'board_id' => 'required|exists:boards,id',
        ]);

        // Update the existing update with validated data
        $update->update($validatedData);

        return response()->json($update);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Update $update)
    {
        $update->delete();

        return response()->json(['message' => 'Update deleted successfully']);
    }
}
