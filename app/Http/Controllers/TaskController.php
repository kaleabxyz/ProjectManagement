<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Events\TaskCreatedEvent;
use App\Events\TaskUpdatedEvent;
use Illuminate\Support\Facades\Log;


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
        Activity::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(), // The user who created the task
            'action' => 'created',
            'description' => "Task '{$task->task_name}' was created",
        ]);
    
        // Trigger an event to notify board members
        event(new TaskCreatedEvent($task->board->id, $task->id));
        return response()->json(['message' => 'Task created successfully', 'task' => $task], 201);
    }

    /**
     * Display the specified task.
     */
    public function show($id)
    {
        // Find the task by ID with its assigned user and board relationships
        $task = Task::with('assignedUser', 'board','activities','activities.user:id,user_name,email,profile_picture_url')->find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json($task);
    }

    /**
     * Update the specified task in storage.
     */
    public function update($id, Request $request)
{
    // Find the task by ID
    $task = Task::find($id);
    Log::info('Request in task controller:', ['board' => $request->all()]);

    if (!$task) {
        return response()->json(['message' => 'Task not found'], 404);
    }

    // Validate the incoming request data
    $validatedData = $request->validate([
        'field' => 'required|string',
        'value' => 'required', // Validate the value
        // other validation rules...
    ]);

    // Initialize the description
    $description = "";

    // Check if the field is 'assigned_to' and fetch the user name
    if ($validatedData['field'] === 'assigned_to') {
        $user = User::find($validatedData['value']);
        if ($user) {
            $description = "Task assigned to '{$user->user_name}'"; // Use the user's name
        } else {
            $description = "Task assigned to user ID '{$validatedData['value']}' (user not found)";
        }
    } else {
        // Use the field name and value for other fields
        $description = "Task field '{$validatedData['field']}' was updated to '{$validatedData['value']}'";
    }

    // Update the task with the validated data
    $task->update([$validatedData['field'] => $validatedData['value']]);

    // Create the activity
    Activity::create([
        'task_id' => $task->id,
        'user_id' => auth()->id(), // The user who made the update
        'action' => 'updated',
        'description' => $description,
    ]);

    // Trigger an event to notify board members
    event(new TaskUpdatedEvent($task->board->id, $task->id));

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
