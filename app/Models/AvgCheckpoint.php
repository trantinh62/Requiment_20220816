<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvgCheckpoint extends Model
{
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'checkpoint_id',
        'user_id',
        'avg_attitude',
        'avg_performance',
        'avg_teamwork',
        'avg_training',
        'avg_adhere',
    ];
}
