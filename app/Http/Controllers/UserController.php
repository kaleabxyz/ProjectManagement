<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }
    
    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // You may not need this for an API. For web apps, return a view.
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:255|unique:users',
            'profile_picture_url' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'location' => 'nullable|string|max:255',
            'birthday' => 'nullable|string|max:255',
            'skype' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'mobile_phone' => 'nullable|string|max:255',
            'working_status' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'user_name' => $request->user_name,
            'profile_picture_url' => $request->profile_picture_url,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'location' => $request->location,
            'birthday' => $request->birthday,
            'skype' => $request->skype,
            'job_title' => $request->job_title,
            'phone' => $request->phone,
            'mobile_phone' => $request->mobile_phone,
            'working_status' => $request->working_status,
        ]);

        return response()->json($user, 201);
    }

   /**
     * Get a user by ID.
     */
    public function show($id): JsonResponse
    {
        $user = User::with(['boardsCreated', 'boardsOwned', 'boardsTrashed'])->findOrFail($id);

    // Check if the user was found
    if ($user) {
        // Structure the response data
        $data = [
            'user' => [
                'id' => $user->id,
                'user_name' => $user->user_name,
                'profile_picture_url' => $user->profile_picture_url,
                'email' => $user->email,
                'location' => $user->location,
                'birthday' => $user->birthday,
                'skype' => $user->skype,
                'job_title' => $user->job_title,
                'phone' => $user->phone,
                'mobile_phone' => $user->mobile_phone,
                'working_status' => $user->working_status,
            ],
            'boards' => [
                'owned' => $user->boardsOwned,
                'created' => $user->boardsCreated,
                'trashed' => $user->boardsTrashed,
            ],
        ];

        return response()->json($data);

        return response()->json(['message' => 'User not found'], 404);
    }
}
    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // You may not need this for an API. For web apps, return a view.
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'sometimes|string|max:255|unique:users,user_name,' . $user->id,
            'profile_picture_url' => 'nullable|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8',
            'location' => 'nullable|string|max:255',
            'birthday' => 'nullable|string|max:255',
            'skype' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'mobile_phone' => 'nullable|string|max:255',
            'working_status' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->update($request->only([
            'user_name',
            'profile_picture_url',
            'email',
            'location',
            'birthday',
            'skype',
            'job_title',
            'phone',
            'mobile_phone',
            'working_status',
        ]));

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return response()->json($user);
    }
    public function login(Request $request)
{
$credentials = $request->only('email', 'password');
try {
$token = Auth::attempt($credentials);
if (!$token) {
return response()->json(['error' => 'Invalid Credentials'], 400);
}
}
catch (Exception $e) {
return response()->json(['error' => 'Could not create token'], 500);
}
$user = Auth::user()->load(['boardsCreated','boardsOwned','boardsTrashed']);
return response()->json([
'status' => 'success',
'user' => $user,
'authorisation' => [
'token' => $token,
'type' => 'bearer',
]
]);
}

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}
