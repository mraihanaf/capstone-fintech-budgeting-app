<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
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

        if($user->status == 'deactive') {
            return response()->json([
                'message' => 'Your account is deactive, please contact admin to activated'
            ], 403);
        }

        $user->tokens()->where('created_at', '<', now()->subDays(2))->delete();

        $token = $user->createToken($validated['email'])->plainTextToken;

        $expiresAt = Carbon::now()->addDays(2);


        return response()->json([
            'message' => 'User login success.',
            'data' => new UserResource($user),
            'token' => $token,
            'expires_at' => $expiresAt
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

        response()->json([
            'message' => 'User login success.',
            'data' => new UserResource($user),
            'token' => $token
        ], 200);

        return redirect("http://localhost:5173/google-callback?token=$token");
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logout success.'
        ], 200);
    }
}
