<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Review;
use App\Models\Checkpoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function groupCheckpoint()
    {
        // $checkpoints = DB::table('reviews', 'reviews')
        //     ->join('checkpoints', 'checkpoints.id', '=', 'reviews.checkpoint_id') 
        //     ->where('reviews.user_id', Auth::user()->id)
        //     ->select([
        //         'reviews.checkpoint_id',
        //         'checkpoints.*'
        //     ])
        //     ->groupBy('reviews.checkpoint_id')
        //     ->get();
        // return response()->apiSuccess($checkpoints);


        $checkpoints = Review::with('nameCheckpoint')->where('user_id', Auth::user()->id)
            ->groupBy('checkpoint_id') 
            ->select('checkpoint_id')->get();
        return response()->apiSuccess($checkpoints);

    }
}
