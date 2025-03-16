<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function update(ProfileRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();
        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => new UserResource($user)
        ]);
    }
}
