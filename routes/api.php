<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
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
    Route::post('invite', 'invite');
    Route::get('register', 'getLink');
    Route::put('register', 'register')->name('register');

    Route::post('login', 'login');
    Route::post('jwt', 'generateJwt');
});
Route::controller(InviteEmailController::class)->group(function () {
    Route::post('invite', 'sendEmail');
});
Route::middleware('auth:sanctum')->group(function(){
    Route::prefix('user')->controller(UserController::class)->group(function () {
        Route::get('/', 'index');
        Route::put('/update', 'updateMaProfile');
    });
});