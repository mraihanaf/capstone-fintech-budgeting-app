<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function UpdateProfile(Request $request) {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::find($request->user()->id);

        if($request->filled('name')) {
            $user->name = $request->name;
        }
        if($request->filled('email')) {
            $user->email = $request->email;
        }
        if($request->filled('phone')) {
            $user->phone = $request->phone;
        }

        $user->save();

        return response()->json(['user' => $user, "message" => "Profile updated successfully"]);
    }

    public function ChangePassword(Request $request) {
        try {

            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            $user = $request->user();

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'Current password is incorrect'], 400);
            }

            if (Hash::check($request->new_password, $user->password)) {
                return response()->json(['message' => 'New password cannot be the same as the old password'], 400);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json(['message' => 'Password updated successfully'], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }
}
