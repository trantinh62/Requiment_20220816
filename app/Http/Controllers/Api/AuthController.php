<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterByEmailRequest;

class AuthController extends Controller
{
    public function getLink(Request $request)
    {
        $key = 'example_key';
        $data = JWT::decode($request['token'], new Key($key, 'HS256'));
        $user = User::where('email',$data->email)->first();

        return response()->apiSuccess($user);
    }

    public static function generateJwt($email){
        $key = 'example_key';
        $now_seconds = time();
        $payload = [
            'email' => $email,
            'iat' => $now_seconds,
            "exp" => $now_seconds + 604800,
        ];
        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }

    public function register(RegisterByEmailRequest $request)
    {
        $key = 'example_key';
        $emailJwt = JWT::decode($request['token'], new Key($key, 'HS256'));
        $data = $request->all();
        $user = User::where('email',$emailJwt->email)->first();
        try {
            if (User::where('last_name',null)->first()) {
                $user->update($data);
            } else {
                return response()->apiError('bạn đã đăng ký trước đó');
            }
            
            return response()->apiSuccess($user);
 
        } catch (Exception $e) {
            return response()->json([
                'data' => null,
                'code'=> 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email','password')))
        {
            return response()->apiError('Unauthorized');
        }
        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        $user['token'] = $token;
        return response()->apiSuccess($user);
    }

}
