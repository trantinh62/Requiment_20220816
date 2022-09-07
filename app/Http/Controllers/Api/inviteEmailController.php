<?php

namespace App\Http\Controllers\api;

use Mail;
use App\Models\User;
use App\Mail\MailNotify;
use Illuminate\Http\Request;
use App\Mail\MailResetPassword;
use App\Http\Controllers\Controller;
use App\Http\Requests\InviteEmailRequest;
use App\Http\Controllers\Api\AuthController;

class InviteEmailController extends Controller
{
    public function sendEmail(InviteEmailRequest $request)
    {
        $data = $request->all();
        $data['token'] = AuthController::setTokenEmailRegister($data['email']);
        try {
            $checkUser = User::where('email', $data['email'])->Where('password', null)->first();
            if ($checkUser) 
            {
                Mail::to($data['email'])->send(new MailNotify($data));
                return response()->apiSuccess($checkUser);
            }
            $user = User::create($data);
            Mail::to($data['email'])->send(new MailNotify($data));     
        } catch (\Exception $e) {
            return response()->apiError('Email sending failed');
        }

        return response()->apiSuccess($user);
    }
    public function sendMailPassword(Request $request)
    {
        $data = $request->all();
        $user = User::where('email',$request['email'])->first();
        $disable = User::where('email', $request['email'])->where('status', 'disable')->first();
        if($disable){
            return response()->json([
                'status' => 403,
                'message' => 'tài khoản đã bị khóa',
            ],403);
        }
        if ($user) {
            $data['token'] = AuthController::setTokenJwtResetPassword($data['email']);
            Mail::to($data['email'])->send(new MailResetPassword($data));

            return response()->apiSuccess($user);

        } else {
            return response()->apiError('Email bạn nhập không đúng');
        }
    }
}
