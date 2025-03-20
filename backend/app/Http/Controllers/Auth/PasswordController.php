<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Password\ChangeRequest;
use App\Http\Requests\Password\ForgotRequest;
use App\Http\Requests\Password\ResetRequest;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordController extends Controller
{
    public function forgot(ForgotRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'User with this email does not exist.',
            ], 404);
        }

        $token = Password::createToken($user);
        $user->notify(new ResetPasswordNotification($token));

        return response()->json([
            'message' => 'Password reset link has been sent to your email.'
        ], 200);
    }

    public function reset(ResetRequest $request)
    {
        $validated = $request->validated();

        $status = Password::reset(
            $validated,
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Reset password success.'],201)
            : response()->json(['message' => 'Invalid or expired token.'], 400);
    }

    public function change(ChangeRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();

        if (Hash::check($validated['new_password'], $user->password)) {
            abort(400, 'New password cannot be the same as the old password.');
        }

        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return response()->json([
            'message' => 'Password updated successfully'
        ], 200);
    }
}
