<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $key = 'example_key';
        JWT::decode($request['token'], new Key($key, 'HS256'));
        dd('a');

        
    }


    public static function generateJwt(){
        $key = 'example_key';
        $now_seconds = time();
        $payload = [
            'username' => 'abc',
            'aud' => 'http://example.com',
            'iat' => $now_seconds,
            "exp" => $now_seconds + 50,
        ];

        /**
         * IMPORTANT:
         * You must specify supported algorithms for your application. See
         * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
         * for a list of spec-compliant algorithms.
         */
        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }
}
