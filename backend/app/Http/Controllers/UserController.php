<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        return response()->json([
            'message' => 'Get user success.',
            'data' => new UserResource($user),
        ]);
    }

}
