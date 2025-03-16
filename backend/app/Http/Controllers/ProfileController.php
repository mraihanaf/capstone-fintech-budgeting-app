<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $user->update($request->only(['name', 'email', 'phone']));

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => new UserResource($user)
        ]);
    }
}