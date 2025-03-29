<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponseTrait; // Add the trait

    public function getUsers()
    {
        $users = User::all();
        return $this->successResponse($users, "Users retrieved successfully");
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->errorResponse("User not found", 404);
        }

        return $this->successResponse($user, "User found");
    }

    public function store(Request $request)
    {
        // Validate input
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
            'gender' => 'required|in:Male,Female,Other'
        ]);

        // Store user
        return response()->json(['status' => 'success', 'message' => 'User created successfully']);
    }
}
