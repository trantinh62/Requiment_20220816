<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Checkpoint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CkeckpointController extends Controller
{
    public function index()
    {
        $user = User::all();
        return response()->apiSuccess($user);
    }

    public function createCkeckpoint(Request $request)
    {
        $data = $request->all();
        $checkpoint = Checkpoint::create($data);
        
        return response()->apiSuccess($checkpoint);
        

    }
}
