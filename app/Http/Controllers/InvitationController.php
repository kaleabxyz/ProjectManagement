<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
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
            'user_id' => 'required|exists:users,id',
            'team_id' => 'required|exists:teams,id',
            'status' => 'required|string',
            'email' => 'required|email',
            'token' => 'required|unique:invitations,token',
        ]);

        $invitation = Invitation::create($request->all());

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
