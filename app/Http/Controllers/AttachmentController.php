<?php

// app/Http/Controllers/AttachmentController.php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Board;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AttachmentController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240',
            'board_id' => 'required|exists:boards,id',
            'task_id' => 'nullable|exists:tasks,id',
            'caption' => 'nullable|string|max:255', // Validate caption
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $file = $request->file('file');
        $boardId = $request->input('board_id');
        $taskId = $request->input('task_id');
        $caption = $request->input('caption'); // Get the optional caption
        $userId = Auth::id(); // Get the authenticated user ID

        $folder = "boards/{$boardId}/";
        if ($taskId) {
            $folder .= "tasks/{$taskId}";
        }

        // Store the file
        $filePath = $file->store($folder, 'public');

        // Save the attachment record
        $attachment = Attachment::create([
            'board_id' => $boardId,
            'task_id' => $taskId,
            'user_id' => $userId,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'caption' => $caption, // Store the optional caption
        ]);

        return response()->json(['message' => 'File uploaded successfully', 'attachment' => $attachment]);
    }

    // Method to download or retrieve a file
    public function download($id)
{
    $attachment = Attachment::findOrFail($id);

    // Ensure file exists
    if (!Storage::disk('public')->exists($attachment->file_path)) {
        return response()->json(['error' => 'File not found.'], 404);
    }

    $file = Storage::disk('public')->path($attachment->file_path);
    $fileName = basename($file);

    // Ensure correct headers for binary data
    return response()->streamDownload(function () use ($file) {
        readfile($file);
    }, $fileName, [
        'Content-Type' => mime_content_type($file),
        'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
    ]);
}


}
