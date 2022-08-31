<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
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
        'review_id',
        'attitude',
        'performance',
        'teamwork',
        'training',
        'adhere',
        'strength',
        'weakness',
    ];

}
