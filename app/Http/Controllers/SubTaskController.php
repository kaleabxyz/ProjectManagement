<?php

namespace App\Http\Controllers;

use App\Models\SubTask;
use Illuminate\Http\Request;

class SubTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subTasks = SubTask::all();
        return response()->json($subTasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'task_name' => 'required|string|max:255',
            'status' => 'nullable|string|in:In Progress,Completed,Not Started',
            'priority' => 'nullable|string|in:Critical,Low,Medium,High',
            'due_date' => 'required|date',
            'assigned_to' => 'nullable|exists:users,id',
            'budget' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'is_trashed' => 'nullable|boolean',
            'trashed_at' => 'nullable|date',
            'trashed_by' => 'nullable|exists:users,id',
            'column' => 'nullable|integer',
            'task_id' => 'required|exists:tasks,id',
        ]);

        $subTask = SubTask::create($validatedData);

        return response()->json($subTask, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SubTask $subTask)
    {
        return response()->json($subTask);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubTask $subTask)
    {
        $validatedData = $request->validate([
            'task_name' => 'required|string|max:255',
            'status' => 'nullable|string|in:In Progress,Completed,Not Started',
            'priority' => 'nullable|string|in:Critical,Low,Medium,High',
            'due_date' => 'required|date',
            'assigned_to' => 'nullable|exists:users,id',
            'budget' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'is_trashed' => 'nullable|boolean',
            'trashed_at' => 'nullable|date',
            'trashed_by' => 'nullable|exists:users,id',
            'column' => 'nullable|integer',
            'task_id' => 'required|exists:tasks,id',
        ]);

        $subTask->update($validatedData);

        return response()->json($subTask);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubTask $subTask)
    {
        $subTask->delete();

        return response()->json(['message' => 'SubTask deleted successfully']);
    }
}
