<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Checkpoint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CkeckpointController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->checkpoints;

        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $data = $request->all();
        $checkpoint = Checkpoint::create($data);
        
        return response()->apiSuccess($checkpoint);
    }
    public function show(Checkpoint $id)
    {
        return response()->apiSuccess($id);
    }
}
