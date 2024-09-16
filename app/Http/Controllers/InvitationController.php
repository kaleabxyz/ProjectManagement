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
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    /**
     * Display a listing of the invitations.
     */
    public function index()
    {
        $invitations = Invitation::with(['inviter', 'board'])->get();
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
        Notification::create([
            'user_id' => $request->invited,
            'icon' => 1,
            'body' => 'You have been invited to join the board ' . $request->board_name,
            'notifiable_id' => $request->invited,
            'notifiable_type' => 'App\\Models\\User',
// Or your specific URL
            'read' => false,
            'invitation_id' => $invitation->id, // Link to the invitation
        ]);

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
    public function acceptInvitation($invitationId)
{
    // Fetch the invitation
    $invitation = Invitation::find($invitationId);
    
    if (!$invitation) {
        return response()->json(['error' => 'Invitation not found'], 404);
    }

    // Get the board from the invitation
    $board = Board::find($invitation->board);
    
    if (!$board) {
        return response()->json(['error' => 'Board not found'], 404);
    }

    // Get the invited user
    $user = User::find($invitation->invited);
    
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // Fetch the user's main workspace ordered by creation date
    $workspace = $user->workspaces()->orderBy('created_at', 'asc')->first();
    
    if (!$workspace) {
        return response()->json(['error' => 'User has no workspaces'], 404);
    }

    // Attach the board to the workspace using the pivot table
    $workspace->boards()->attach($board->id);

    // Fetch the team from the invitation
    $team = Team::find($invitation->team);
    
    if (!$team) {
        return response()->json(['error' => 'Team not found'], 404);
    }

    // Check if the user is already a member of the team
    $existingMember = TeamMember::where('team', $team->id)
        ->where('member', $user->id)
        ->first();

    if (!$existingMember) {
        // Add the invited user to the team_members table
        TeamMember::create([
            'team' => $team->id,
            'member' => $user->id,
            'role' => $invitation->role ?? 'Member', // Default to 'Member' if no role is specified
        ]);
    }

    // Optionally, update the invitation status to 'accepted'
    $invitation->update(['status' => 'accepted']);

    return response()->json(['message' => 'Board added to workspace and user added to team successfully']);
}
public function declineInvitation(Request $request)
{
    $user = auth()->user();

    // Find the invitation
    $invitation = Invitation::find($request->invitation_id);

    if (!$invitation) {
        return response()->json(['error' => 'Invitation not found'], 404);
    }

    // Update the invitation status to declined
    $invitation->update(['status' => 'declined']);

    // Mark the notification as read
    $notification = $user->notifications()->find($request->notification_id);
    if ($notification) {
        $notification->markAsRead();
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
