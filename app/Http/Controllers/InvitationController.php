<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\Board;
use App\Events\InviteSent;
use App\Models\Invitation;
use App\Models\TeamMember;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Events\NewInviteEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    /**
     * Display a listing of the invitations.
     */
    public function index()
    {
        $invitations = Invitation::with(['inviter'])->get();
        return response()->json($invitations);
    }

    /**
     * Store a newly created invitation in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'invited' => 'required|exists:users,id',
            'board' => 'required|exists:boards,id',
            'team' => 'required|exists:teams,id',
            'role' => 'required|in:Member,Viewer(Read-only)',
            'email' => 'required|email',
        ]);

        // Create the invitation
        $invitation = Invitation::create([
            'inviter' => Auth::id(),
            'invited' => $request->invited,
            'board' => $request->board,
            'team' => $request->team,
            'status' => 'pending', // Assuming default status
            'role' => $request->role,
            'email' => $request->email,
            'token' => Str::random(60),
        ]);
        $notification =  Notification::create([
            'user_id' => $request->invited,
            'icon' => 1,
            'body' => 'You have been invited to join the board ' . $request->board_name,
            'notifiable_id' => $request->invited,
            'notifiable_type' => 'App\\Models\\User',
// Or your specific URL
            'read' => false,
            'invitation_id' => $invitation->id, // Link to the invitation
        ]);
        $inviter = User::find(Auth::id());

    // Dispatch the event with notification, invitation, and inviter data
    event(new NewInviteEvent($notification, $invitation, $inviter));
        
        // Find the invited user and inviter (the current user)
        

        // Fire the InviteSent event
       

        return response()->json($invitation, 201);
    }

    /**
     * Display the specified invitation.
     */
    public function show($id)
    {
        $invitation = Invitation::with(['user', 'team'])->findOrFail($id);
        return response()->json($invitation);
    }

    /**
     * Update the specified invitation in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'sometimes|string',
            'email' => 'sometimes|email',
            'token' => 'sometimes|unique:invitations,token,' . $id,
        ]);

        $invitation = Invitation::findOrFail($id);
        $invitation->update($request->all());

        return response()->json($invitation);
    }
    public function acceptInvitation(Request $request, $invitationId)
{
    $invitation = Invitation::find($invitationId);
    
    if (!$invitation) {
        return response()->json(['error' => 'Invitation not found'], 404);
    }

    $board = Board::find($invitation->board);
    if (!$board) {
        return response()->json(['error' => 'Board not found'], 404);
    }

    $user = User::find($invitation->invited);
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    $workspace = $user->workspaces()->orderBy('created_at', 'asc')->first();
    if (!$workspace) {
        return response()->json(['error' => 'User has no workspaces'], 404);
    }

    $workspace->boards()->attach($board->id);

    $team = Team::find($invitation->team);
    if (!$team) {
        return response()->json(['error' => 'Team not found'], 404);
    }

    $existingMember = TeamMember::where('team', $team->id)
        ->where('member', $user->id)
        ->first();

    if (!$existingMember) {
        TeamMember::create([
            'team' => $team->id,
            'member' => $user->id,
            'role' => $invitation->role ?? 'Member',
        ]);
    }

    $invitation->update(['status' => 'accepted']);
    
    // Mark the notification as read
    $notification = $user->notifications()->find($request->notification_id);
    if ($notification) {
        $notification->update(['read' => true]);
    }

    return response()->json(['message' => 'Board added to workspace and user added to team successfully']);
}

public function declineInvitation(Request $request)
{
    $user = auth()->user();
    $invitation = Invitation::find($request->invitation_id);

    if (!$invitation) {
        return response()->json(['error' => 'Invitation not found'], 404);
    }

    $invitation->update(['status' => 'declined']);

    // Mark the notification as read
    $notification = $user->notifications()->find($request->notification_id);
    if ($notification) {
        $notification->update(['read' => true]);
    }

    return response()->json(['message' => 'Invitation declined and notification marked as read']);
}


    /**
     * Remove the specified invitation from storage.
     */
    public function destroy($id)
    {
        $invitation = Invitation::findOrFail($id);
        $invitation->delete();

        return response()->json(['message' => 'Invitation deleted successfully.']);
    }
}
