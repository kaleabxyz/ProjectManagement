<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    
    // Fetch notifications for the logged-in user
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        $notifications = $user->notifications()
        ->where('notifiable_id', $user->id) // Ensure you use the correct user ID
        ->where('notifiable_type', 'App\\Models\\User') // Ensure correct notifiable_type
        ->where('read',false) // Use read_at if that's the column for unread notifications
        ->with(['invitation','invitation.board','invitation.inviter'])
        ->get();
        Log::info('Validated notification new data:', [$notifications,$user->id]);
    return response()->json($notifications);
    }

    // Mark a notification as read
    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            $notification->update(['read' => true]);
            return response()->json(['message' => 'Notification marked as read']);
        }
        return response()->json(['error' => 'Notification not found'], 404);
    }

    // Send notification (invoked when invitation is sent)
    public function store(Request $request)
    {
        $notification = Notification::create([
            'user_id' => $request->user_id,
            'icon' => $request->icon,
            'body' => $request->body,
            'read' => false,
            'notifiable_id' => $request->notifiable_id,
        'notifiable_type' => $request->notifiable_type,
        
        ]);

        // Optionally, broadcast the notification event here for real-time updates
        // event(new NotificationSent($notification));

        return response()->json(['message' => 'Notification sent successfully']);
    }
    
    public function update(Request $request)
{
    $notification = Notification::find($request->notification_id);
    
    if (!$notification) {
        return response()->json(['error' => 'Notification not found'], 404);
    }

    $notification->update(['read' => $request->read]);

    return response()->json(['message' => 'Notification updated successfully']);
}
}
