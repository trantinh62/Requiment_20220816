<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\RegisterByEmailRequest;

class AuthController extends Controller
{
    public static function setTokenEmailRegister($email){
        $key = 'example_key';
        $now_seconds = time();
        $payload = [
            'email' => $email,
            'iat' => $now_seconds,
            "exp" => $now_seconds + 1000000,
        ];
        $jwt = JWT::encode($payload, $key, 'HS256');
        
        return $jwt;
    }

    public function getLink(Request $request)
    {
        $key = 'example_key';
        $data = JWT::decode($request['token'], new Key($key, 'HS256'));
        dd($data);
        $user = User::where('email',$data->email)->first();

        return response()->apiSuccess($user);
    }

    public function register(RegisterByEmailRequest $request)
    {
        $key = 'example_key';
        $emailJwt = JWT::decode($request['token'], new Key($key, 'HS256'));
        $data = $request->all();
        $data['status'] = 'enable';
        $user = User::where('email',$emailJwt->email)->first();
        try {
            if (User::where('last_name',null)->first()) {
                $user->update($data);
            } else {
                return response()->apiError('bạn đã đăng ký trước đó');
            }

            return response()->apiSuccess($user);
 
        } catch (Exception $e) {
            return response()->apiError('lỗi');
        }
    }

    public static function setTokenJwtResetPassword($email){
        $key = 'passssssword';
        $now_seconds = time();
        $payload = [
            'email' => $email,
            'iat' => $now_seconds,
            "exp" => $now_seconds + 600,
        ];
        $jwt = JWT::encode($payload, $key, 'HS256');
        
        return $jwt;
    }

    public function getIndexResetPassword(Request $request)
    {
        $key = 'passssssword';
        $data = JWT::decode($request['token'], new Key($key, 'HS256'));
        $user = User::where('email',$data->email)->first();
        return response()->apiSuccess($user);
    }

    public function getUpdateResetPassword(ChangePasswordRequest $request)
    {
        $key = 'passssssword';
        $emailJwt = JWT::decode($request['token'], new Key($key, 'HS256'));
        $data = $request->all();
        $user = User::where('email',$emailJwt->email)->first();
        try {
            
            $user->update($data);
           
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
        $enable = User::where('email', $request['email'])->where('status', 'enable')->first();
        $disable = User::where('email', $request['email'])->where('status', 'disable')->first();
        if ($enable) {
            if (!Auth::attempt($request->only('email','password')))
            {
                return response()->apiError('Account or password is not precision');
            }
            $user = User::where('email', $request['email'])->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;
            $user['token'] = $token;
            return response()->apiSuccess($user);
        }
        if($disable){
            return response()->json([
                'status' => 403,
                'message' => 'tài khoản đã bị khóa',
            ],403);
        }
        return response()->apiError('tài khoản mật khẩu không chính xác');
    }
}
