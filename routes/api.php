<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CkeckpointController;
use App\Http\Controllers\api\InviteEmailController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::controller(AuthController::class)->group(function () {
    Route::post('jwt', 'setTokenEmailRegister');
    Route::get('register', 'getLink');
    Route::put('register', 'register')->name('register');
    //Api reset password
    Route::get('reset_password', 'getIndexResetPassword');
    Route::put('reset_password', 'getUpdateResetPassword')->name('resetPassword');
    Route::post('login', 'login');
});
Route::controller(InviteEmailController::class)->group(function () {
    Route::post('invite', 'sendEmail');
    //invite send maail reset password
    Route::post('invite_reset_password', 'sendMailPassword');
});
Route::middleware('auth:sanctum')->group(function(){
    Route::controller(ProfileController::class)->prefix('profile')->group(function () {
        Route::get('/', 'index');
        Route::get('/detail', 'detail');
        Route::put('/', 'update');
        Route::put('/change_password', 'changePassword');
        Route::post('/logout', 'logout');
    });
    Route::controller(CkeckpointController::class)->prefix('checkpoint')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
    });
    Route::controller(ReviewController::class)->prefix('review')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{checkpoint}', 'show');

    });
});