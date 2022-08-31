<?php

namespace App\Http\Controllers\Api;

use App\Models\Review;
use App\Models\Checkpoint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        // $this->authorize('create', User::class);

        $arr = [];
        $data = $request->all();
        foreach($data['review_id'] as $value) {
            $review = Review::create([
                'checkpoint_id' => $data['checkpoint_id'],
                'user_id' => $data['user_id'],
                'review_id' => $value,
            ]);
            $arr[] = $review;
        }
        return response()->apiSuccess($arr);

    }

    public function show(Checkpoint $checkpoint)
    {
        $checkpoint->assign;
        
        return response()->apiSuccess($checkpoint);


    }
}

