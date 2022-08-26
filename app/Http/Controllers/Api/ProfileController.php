<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    public function index()
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

}
