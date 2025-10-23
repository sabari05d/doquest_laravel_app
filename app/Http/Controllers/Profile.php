<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Profile extends Controller
{
    // View Profile
    public function profileIndex()
    {
        $user = Auth::user();

        return view('profile.profile', [
            'user' => $user
        ]);
    }

    // Update Profile
    public function updateProfile(Request $request)
    {
        $user = $request->user(); // authenticated user

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id, // <-- FIXED: should be string, not email
            'mobile' => 'required|string|max:15|unique:users,mobile,' . $user->id,
        ]);

        $user->update($validated); // simpler way

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }

}
