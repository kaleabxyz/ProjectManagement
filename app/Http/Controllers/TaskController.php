<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     */
    public function index()
    {
        // Retrieve all tasks with their assigned user and board relationships
        $tasks = Task::with('assignedUser', 'board')->get();
        return response()->json($tasks);
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'task_name' => 'required|string|max:255',
            'status' => 'nullable|string|in:In Progress,Completed,Not Started',
            'showUpdates' => 'boolean',
            'priority' => 'nullable|string|in:Critical,Low,Medium,High',
            'due_date' => 'nullable|date',
            'board_id' => 'required|integer|exists:boards,id',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'budget' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'is_trashed' => 'nullable|boolean',
            'trashed_at' => 'nullable|date',
            'trashed_by' => 'nullable|integer|exists:users,id',
            'column' => 'nullable|integer',
            'subItemVisible' => 'boolean',
        ]);

        // Create a new task using the validated data
        $task = Task::create($validatedData);

        return response()->json(['message' => 'Task created successfully', 'task' => $task], 201);
    }

    /**
     * Display the specified task.
     */
    public function show($id)
    {
        // Find the task by ID with its assigned user and board relationships
        $task = Task::with('assignedUser', 'board')->find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json($task);
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the task by ID
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        // Validate the incoming request data
        $validatedData = $request->validate([
            'task_name' => 'sometimes|required|string|max:255',
            'status' => 'nullable|string|in:In Progress,Completed,Not Started',
            'selectStatus' => 'boolean',
            'selectOwner' => 'boolean',
            'selectPriority' => 'boolean',
            'showUpdates' => 'boolean',
            'priority' => 'nullable|string|in:Critical,Low,Medium,High',
            'due_date' => 'nullable|date',
            'board_id' => 'sometimes|required|integer|exists:boards,id',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'budget' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'is_trashed' => 'nullable|boolean',
            'trashed_at' => 'nullable|date',
            'trashed_by' => 'nullable|integer|exists:users,id',
            'column' => 'nullable|integer',
            'subItemVisible' => 'boolean',
        ]);

        // Update the task with the validated data
        $task->update($validatedData);

        return response()->json(['message' => 'Task updated successfully', 'task' => $task]);
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy($id)
    {
        // Find the task by ID
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        // Delete the task
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
