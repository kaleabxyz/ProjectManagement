<?php

namespace App\Http\Controllers;

use App\Models\Update;
use Illuminate\Http\Request;
use App\Events\TaskUpdatedEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $updates = Update::with(['user','board', 'task', 'user.teamMembers'])->get()
        ->map(function ($update) {
            // Fetch the role of the user from team_members table
            $teamMember = $update->user->teamMembers->where('board', $update->board_id)->first();
            $update->user_role = $teamMember ? $teamMember->role : 'Not Assigned';
            return $update;
        });
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
                'reply' => 'nullable|boolean',
                'parent_id' => 'nullable|exists:updates,id',
                'board_id' => 'required|exists:boards,id',
            ]);

            $update = Update::create($validatedData);
            $update->readers()->attach($validatedData['user_id'], ['is_read' => false]);
            event(new TaskUpdatedEvent($update->board_id, null, $update->id));

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
    public function show(Request $request, Update $update)
    {
        // Get the user ID from the request
        $userId = $request->input('user_id');
    
        // Check if the user has read the update
        $readStatus = $update->readers()
                             ->where('user_id', $userId)
                             ->first()
                             ->pivot
                             ->is_read ?? false;
    
        // Fetch the user role from the team_members table through team
        $teamMember = $update->user->teamMembers()
                                 ->whereHas('team', function ($query) use ($update) {
                                     $query->where('board', $update->board_id);
                                 })
                                 ->first();
                                 
        $userRole = $teamMember ? $teamMember->role : 'Not Assigned';
    
        // Load the update data along with the read status and user role
        $updateData = $update->toArray();
        $updateData['is_read'] = $readStatus;
        $updateData['user_role'] = $userRole;
    
        return response()->json($updateData);
    }
    




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Update $update)
    {
        $validatedData = $request->validate([
           
            'user_id' => 'nullable|exists:users,id',
            'content' => 'nullable|string',
           
            'has_reply' => 'nullable|boolean',

      
           
            
        ]);

        // Update the existing update with validated data
        $update->update($validatedData);

        return response()->json($update);
    }
    public function markAsRead($id, Request $request)
{
    $userId = $request->input('user_id');

    // Update the read status in the pivot table
    DB::table('user_update_read_status')
        ->where('update_id', $id)
        ->where('user_id', $userId)
        ->update(['is_read' => true]);

    return response()->json(['success' => true]);
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
