<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ProfileRequest;
use App\Http\Resources\LogResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function update(ProfileRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();
        $oldName = $user->name;
        $oldEmail = $user->email;
        $oldPhone = $user->phone;
        $user->update($validated);

        $log = auth('api')->user()->logs()->create([
            'action' => "Update profile",
            'details' => "{$oldEmail} -> {$user->email} - {$oldName} -> {$user->name} - {$oldPhone} -> {$user->phone}"
        ]);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => new UserResource($user),
            'log' => new LogResource($log)
        ]);
    }
}
