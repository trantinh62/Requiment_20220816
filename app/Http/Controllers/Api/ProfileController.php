<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\ChangePasswordRequest;

class ProfileController extends Controller
{
    public function index()
    {
        $user = user::whereNotNull('password')->get();
        return response()->apiSuccess($user);
    }
    public function detail()
    {
        $user = User::find(Auth::id())->toArray();
        return response()->apiSuccess($user);
    }

    public function update(UpdateProfileRequest $request)
    {
        $profile = Auth::user();
        $data = $request->all();
        try {
            $profile->update($data);
        } catch (\Exception $e) {
            return response()->apiError('update profile error');
        }

        return response()->apiSuccess($profile);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $profile = Auth::user();
        $data = $request->all();
        if (!Hash::check($data['old_password'], $profile->password)) {
            return response()->apiError('old password is incorrect');
        }
        try {
            $profile->password = $data['password'];
            $profile->save();
        } catch (\Exception $e) {
            return response()->apiError('Change password error');
        }

        return response()->apiSuccess($profile);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->apiSuccess(null);
    }

    public function updateMany(Request $request)
    {
        try {
            if ($request['data'] && !empty($request['data'])) {
                foreach ($request['data'] as $dt) {
                    $dataUpdate = [];
                    if (isset($dt['status'])) {
                        $dataUpdate['status'] = $dt['status'];
                    }
                    if (isset($dt['role_id'])) {
                        $dataUpdate['role_id'] = $dt['role_id'];
                    }
                    User::where('id', $dt['id'])->update($dataUpdate);
                }
            }

            return response()->apiSuccess(true);
        } catch (\Exception $e) {
            return response()->apiSuccess(false);
        }
    }
}
