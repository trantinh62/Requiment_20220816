<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
      dd(123);
    }
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email','password')))
        {
            return response()->json([
                'message' => 'Unauthorized1111'
            ], 401);
        }
        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'mesage' => 'Hi ' . $user->first_name . ', welcome to home',
            'access_token' => $token,
            'token_type'=>'bearer',
        ]);
    }
}
