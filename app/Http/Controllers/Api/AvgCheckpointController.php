<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Checkpoint;
use Illuminate\Http\Request;

class AvgCheckpointController extends Controller
{
    public function getIndexAvgCheckpoint(Request $request) 
    {
        $data = Checkpoint::with('avgCheckpointinfo')->where('id', $request['id'])->get();

        return response()->apiSuccess($data);
    }
}
