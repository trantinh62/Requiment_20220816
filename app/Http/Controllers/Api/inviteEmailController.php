<?php

namespace App\Http\Controllers\Api;

use Mail;
use Exception;
use App\Models\User;
use App\Mail\MailNotify;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\InviteEmailRequest;
use App\Http\Controllers\Api\AuthController;



class inviteEmailController extends Controller
{
    public function sendEmail(InviteEmailRequest $request)
    {
        $data = $request->all();
        $data['token'] = AuthController::generateJwt();
        try {
            $user = User::create($data);
            Mail::to($data['email'])->send(new MailNotify($data));
        } catch (Exception $e) {
            return response()->json([
                'data' => null,
                'code'=> 500,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'data' => $user,
            'status' => true,
        ]);
        
    }
}
