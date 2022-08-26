<?php

namespace App\Http\Controllers\api;

use Mail;
use App\Models\User;
use App\Mail\MailNotify;
use App\Http\Controllers\Controller;
use App\Http\Requests\InviteEmailRequest;

class InviteEmailController extends Controller
{
    public function sendEmail(InviteEmailRequest $request)
    {
        $data = $request->all();
        $data['token'] = AuthController::generateJwt($data['email']);
        try {
            $user = User::create($data);
            Mail::to($data['email'])->send(new MailNotify($data));     
        } catch (Exception $e) {
            return response()->apiError('Email sending failed');
        }

        return response()->apiSuccess($user);
    }
}
