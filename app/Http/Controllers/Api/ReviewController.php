<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function reviewListAssign(Request $request )
    {
        if ($request->boolean(('useUserId'))) {
            $user = User::with(['userReview.nameCheckpoint', 'userReview.userInfo', 'userReview.reviewInfo'])->find(Auth::id());
        } else {
            $user = User::with(['assignReview.nameCheckpoint', 'assignReview.userInfo', 'assignReview.reviewInfo'])->find(Auth::id());
        }
        // $user = User::with(['assignReview' => function ($query) {
        //     $query->with('nameCheckpoint');
        // }])->find(Auth::id());
        // $user = User::with(['assignReview.nameCheckpoint', 'assignReview.userInfo', 'assignReview.reviewInfo'])->find(Auth::id());

        return response()->apiSuccess($user);
    }

    public function createReviewPoint(Request $request)
    {
        $data = $request->all();
        $review = Review::where('id', $request['id'])->where('review_id', Auth::id())->first();
        if ($review) {
            try {
                if (is_null($review->attitude)) {
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

    public function checkListAssgin(Request $request)
    {
        $review = Review::where('checkpoint_id', $request['checkpoint_id'])->where('user_id', $request['user_id'] )->get();
        return $review;
    }

    public function getUserBeChecked(Request $request)
    {
        $review = Review::where('checkpoint_id', $request['checkpoint_id'])->where('user_id', $request['user_id'] )->get();
        return $review;
    }

    public function getReview(Request $request)
    {
        $review = Review::where('checkpoint_id', $request['checkpoint_id'])->where('user_id', Auth::user()->id )->get();
        return $review;
    }

    public function getUserByCheckpoint($checkpointId)
    {
        $user = DB::table('reviews', 'reviews')
            ->join('users', 'users.id', '=', 'reviews.user_id')
            ->where('reviews.checkpoint_id', $checkpointId)
            ->select([
                'reviews.user_id',
                'users.*'
            ])
            ->groupBy('reviews.user_id')
            ->get();
        return response()->apiSuccess($user);
    }

    
}

