<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities for a task.
     */
    public function index($taskId)
    {
        // Fetch activities for the given task ID
        $activities = Activity::where('task_id', $taskId)->with('user')->get();
        return response()->json($activities);
    }

    /**
     * Store a new activity.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'user_id' => 'required|exists:users,id',
            'action' => 'required|string',
            'description' => 'nullable|string',
        ]);

        // Create the activity
        $activity = Activity::create($request->all());
        return response()->json($activity, 201);
    }

    /**
     * Show the details of a specific activity.
     */
    public function show($id)
    {
        // Fetch the activity
        $activity = Activity::with('task', 'user')->findOrFail($id);
        return response()->json($activity);
    }

    /**
     * Delete an activity.
     */
    public function destroy($id)
    {
        // Find and delete the activity
        $activity = Activity::findOrFail($id);
        $activity->delete();
        return response()->json(['message' => 'Activity deleted successfully']);
    }
}
