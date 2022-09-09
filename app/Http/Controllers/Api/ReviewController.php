<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Review;
use App\Models\Checkpoint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
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

    public function reviewListAssign()
    {
        // $user = User::with(['assignReview' => function ($query) {
        //     $query->with('nameCheckpoint');
        // }])->find(Auth::id());
        $user = User::with('assignReview.nameCheckpoint')->find(Auth::id());

        return response()->apiSuccess($user);
    }

    public function createReviewPoint(Request $request)
    {
        $data = $request->all();
        $review = Review::where('id',$request['id'])->where('review_id',Auth::id())->first();
        if ($review) {
            try {
                if (Review::where('attitude',null)->first()) {
                    $review->update($data);
                } else {
                    return response()->apiError('Bạn đã Review cho ID này');
                }
    
                return response()->apiSuccess($review);
     
            } catch (Exception $e) {
                return response()->apiError('lỗi');
            }
        }else {
            return response()->apiError('bạn không có quyền Review ID này');
        }
    }
}

