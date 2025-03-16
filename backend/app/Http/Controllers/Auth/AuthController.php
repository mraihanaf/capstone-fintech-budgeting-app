<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Users\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function register(UserRequest $request)
    {
        $validated = $request->validated();

        $validated["password"] = Hash::make($validated["password"]);
        $user = User::create($validated);

        Auth::login($user);
        $token = $user->createToken($validated['email'])->plainTextToken;

        return response()->json([
            'message' => 'User register success.',
            'data' => new UserResource($user),
            'token' => $token
        ], 200);
    }
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (!Auth::attempt($validated)) {
            return response()->json([
                'message' => 'Invalid email or password.'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken($validated['email'])->plainTextToken;

        return response()->json([
            'message' => 'User login success.',
            'data' => new UserResource($user),
            'token' => $token
        ], 200);
    }

    public function google_redirect()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function google_callback()
    {
        // INI MERAH stateless() NYA BIARIN AJA GPP MASIH JALAN GK ADA ERROR KOK
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::where('email', $googleUser->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => Hash::make('password')
            ]);
        }

        $token = $user->createToken($googleUser->email)->plainTextToken;

        return response()->json([
            'message' => 'User login success.',
            'data' => new UserResource($user),
            'token' => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logout success.'
        ], 200);
    }
}