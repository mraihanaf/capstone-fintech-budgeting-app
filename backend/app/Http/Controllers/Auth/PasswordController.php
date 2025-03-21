<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Password\ChangeRequest;
use App\Http\Requests\Password\ForgotRequest;
use App\Http\Requests\Password\ResetRequest;
use App\Http\Resources\LogResource;
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
                'message' => 'User with this email does not exist.'
            ], 404);
        }

        $token = Password::createToken($user);
        $user->notify(new ResetPasswordNotification($token));

        $log = auth('api')->user()->logs()->create([
            'action' => "Forgot password",
            'details' => "Request change password"
        ]);

        return response()->json([
            'message' => 'Password reset link has been sent to your email.',
            'log' => new LogResource($log)
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

        $log = auth('api')->user()->logs()->create([
            'action' => "Reset Password",
            'details' => $status === Password::PASSWORD_RESET ? 'Reset Password success' : 'Reset Password failed'
        ]);

        return $status === Password::PASSWORD_RESET
            ? response()->json([
                'message' => 'Reset password success.',
                'log' => new LogResource($log)
            ], 201)
            : response()->json([
                'message' => 'Invalid or expired token.',
                'log' => new LogResource($log)
            ], 400);
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

        $log = auth('api')->user()->logs()->create([
            'action' => 'Change password',
            'details' => 'Change password success'
        ]);

        return response()->json([
            'message' => 'Password changed successfully'
        ], 200);
    }
}