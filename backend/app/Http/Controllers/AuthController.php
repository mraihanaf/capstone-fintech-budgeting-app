<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'string|max:20',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone
        ]);

        return response()->json(['user' => $user, 'token' => $user->createToken($request->email)->plainTextToken]);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json(['token' => $user->createToken($request->email)->plainTextToken]);
    }

    public function user(Request $request) {
        return response()->json(['user' => $request->user()]);
    }

    public function google_redirect() {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }


    public function google_callback() {

        // INI MERAH stateless() NYA BIARIN AJA GPP MASIH JALAN GK ADA ERROR KOK
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::where('email', $googleUser->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => Hash::make('password')
            ]);
        }

        $token = $user->createToken($googleUser->email)->plainTextToken;


        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function requestPasswordReset(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = Password::createToken($user);
            $user->notify(new ResetPasswordNotification($token));

            return response()->json(['message' => 'Link reset password telah dikirim ke email Anda.']);
        }

        return response()->json(['message' => 'Email tidak ditemukan'], 404);
    }

    public function resetPassword(Request $request)
    {
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'token' => 'required',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? response()->json(['message' => 'Password berhasil direset.'])
        : response()->json(['message' => 'Token tidak valid atau expired.'], 400);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout successful']);
    }
}
