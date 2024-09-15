<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    /**
     * Display a listing of the invitations.
     */
    public function index()
    {
        $invitations = Invitation::with(['user', 'team'])->get();
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
