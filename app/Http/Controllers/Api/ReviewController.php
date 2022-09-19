<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\AvgCheckpoint;
use Illuminate\Support\Facades\DB;
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
        $review = Review::where('id', $request['id'])
            ->where('review_id', Auth::id())->where('attitude', null)
            ->first();

        if (!$review) {
            return response()->apiError('Bạn đã đánh giá hoặc không có quyền Review ID này');
        }

        // Neu tim thay review phu hop voi dieu kien
        $updated = $review->update($data);
        if (!$updated) {
            return response()->apiError('lỗi');
        }

        // Neu update thanh cong
        if (!$this->hasReviewComplete($request)) {
            $user = Review::with(['reviewInfo'])->where('checkpoint_id', $request['checkpoint_id'])->where('user_id', $request['user_id'])->get();
            $groupLeaderAttitude = 0;
            $groupLeaderPerformance = 0;
            $groupLeaderTeamwork = 0;
            $groupLeaderTraining = 0;
            $groupLeaderAdhere = 0;
            $groupLeaderCount = 0;
            $leaderAttitude = 0;
            $leaderPerformance = 0;
            $leaderTeamwork = 0;
            $leaderTraining = 0;
            $leaderAdhere = 0;
            $leaderCount = 0;
            $memberAttitude = 0;
            $memberPerformance = 0;
            $memberTeamwork = 0;
            $memberTraining = 0;
            $memberAdhere = 0;
            $memberCount = 0;
            foreach ($user as $key => $el) {
                if ($el->reviewInfo->role_id == 1) {
                    $groupLeaderAttitude += $el->attitude;
                    $groupLeaderPerformance += $el->performance;
                    $groupLeaderTeamwork += $el->teamwork;
                    $groupLeaderTraining += $el->training;
                    $groupLeaderAdhere += $el->adhere;
                    $groupLeaderCount ++;
                } else if ($el->reviewInfo->role_id == 2) {
                    $leaderAttitude += $el->attitude;
                    $leaderPerformance += $el->performance;
                    $leaderTeamwork += $el->teamwork;
                    $leaderTraining += $el->training;
                    $leaderAdhere += $el->adhere;
                    $leaderCount ++;
                } else if ($el->reviewInfo->role_id == 3) {
                    $memberAttitude += $el->attitude;
                    $memberPerformance += $el->performance;
                    $memberTeamwork += $el->teamwork;
                    $memberTraining += $el->training;
                    $memberAdhere += $el->adhere;
                    $memberCount ++;
                }
            }
            //Attitude
            $groupLeaderAttitudeAVG = $this->avgCal($groupLeaderAttitude, $groupLeaderCount, 1);
            $leaderAttitudeAVG = $this->avgCal($leaderAttitude, $leaderCount, 2);
            $memberAttitudeAVG = $this->avgCal($memberAttitude, $memberCount, 3);
            //Performance
            $groupLeaderPerformanceAVG = $this->avgCal($groupLeaderPerformance, $groupLeaderCount, 1);
            $leaderPerformanceAVG = $this->avgCal($leaderPerformance, $leaderCount, 2);
            $memberPerformanceAVG = $this->avgCal($memberPerformance, $memberCount, 3);
            // Teamwork
            $groupLeaderTeamworkAVG = $this->avgCal($groupLeaderTeamwork, $groupLeaderCount, 1);
            $leaderTeamworkAVG = $this->avgCal($leaderTeamwork, $leaderCount, 2);
            $memberTeamworkAVG = $this->avgCal($memberTeamwork, $memberCount, 3);
            // Training
            $groupLeaderTrainingAVG = $this->avgCal($groupLeaderTraining, $groupLeaderCount, 1);
            $leaderTrainingAVG = $this->avgCal($leaderTraining, $leaderCount, 2);
            $memberTrainingAVG = $this->avgCal($memberTraining, $memberCount, 3);

            // Adhere
            $groupLeaderAdhereAVG = $this->avgCal($groupLeaderAdhere, $groupLeaderCount, 1);
            $leaderAdhereAVG = $this->avgCal($leaderAdhere, $leaderCount, 2);
            $memberAdhereAVG = $this->avgCal($memberAdhere, $memberCount, 3);

            $sumAttitude = $this->sumAvgCal($groupLeaderAttitudeAVG, $leaderAttitudeAVG, $memberAttitudeAVG);
            $sumPerformance = $this->sumAvgCal($groupLeaderPerformanceAVG, $leaderPerformanceAVG, $memberPerformanceAVG);
            $sumTeamwork = $this->sumAvgCal($groupLeaderTeamworkAVG, $leaderTeamworkAVG, $memberTeamworkAVG);
            $sumTraining = $this->sumAvgCal($groupLeaderTrainingAVG, $leaderTrainingAVG, $memberTrainingAVG);
            $sumAdhere = $this->sumAvgCal($groupLeaderAdhereAVG, $leaderAdhereAVG, $memberAdhereAVG);
            AvgCheckpoint::create([
                'checkpoint_id' => $request['checkpoint_id'],
                'user_id' => $request['user_id'],
                'avg_attitude' => round($sumAttitude),
                'avg_performance' => round($sumPerformance),
                'avg_teamwork' => round($sumTeamwork),
                'avg_training' => round($sumTraining),
                'avg_adhere' => round($sumAdhere),
            ]);    
        }

        return response()->apiSuccess($review);

    }

    public function checkListAssgin(Request $request)
    {
        $review = Review::where('checkpoint_id', $request['checkpoint_id'])->where('user_id', $request['user_id'] )->get();
        return $review;
    }

    public function getReview(Request $request)
    {
        $review = Review::where('checkpoint_id', $request['checkpoint_id'])->where('user_id', Auth::user()->id )->get();
        return $review;
    }
    public function getCheckpointassgin(Request $request)
    {
        $review = Review::where('review_id', Auth::user()->id )->get();
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

    // return true: tat ca gia tri cua attitude trong table reviews khong null
    // return false: nguoc lai
    protected function hasReviewComplete(Request $request)
    {
        $checkReviewIsNull = Review::where('checkpoint_id', $request['checkpoint_id'])
        ->where('user_id', $request['user_id'])
        ->where('attitude', null)->first();
        return $checkReviewIsNull;
    }
    protected function avgCal($point, $count, $role)
    {
        $tgWeight = 30;
        if ($role == 2) {
            $tgWeight = 20;
        } else if ($role == 3) {
            $tgWeight = 50;
        }
        $result = $point / $count * $tgWeight / 100;

        return $result;
    }

    protected function sumAvgCal($gl, $ld, $mb)
    {
        $result = $gl + $ld + $mb;

        return $result;
    }
}
