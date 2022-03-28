<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\Challenge\ChallengeController;
use App\Http\Controllers\Challenge\ChallengeTypeController;
use App\Http\Controllers\SubmittedChallengeController;
use App\Http\Controllers\Team\TeamController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'v1'], function(){
    Route::post('user-registration', [UserController::class, 'store']);
    Route::post('/login', [ApiAuthController::class,'login']);
    Route::post('/logout',[ApiAuthController::class, 'logout']);
    Route::get('/fetch-challenges',[ChallengeController::class, 'userChallenges']);
    Route::get('/challenge/{userId}/{challengeId}', [SubmittedChallengeController::class, 'fetchChallenge']);
    Route::get('/team-wise-submitted/{challengeId}', [SubmittedChallengeController::class, 'teamWiseSuccessfullySubmitted']);
    Route::get('/user-wise-submitted/{challengeId}', [SubmittedChallengeController::class, 'userWiseSuccessfullySubmitted']);
    Route::post('/store-submission', [SubmittedChallengeController::class, 'store']);

    Route::group(['middleware' => ['auth:api']], function(){
        Route::resource('users', UserController::class)->except('store');

        Route::resource('teams', TeamController::class);
        Route::post('team-login',[TeamController::class, 'login']);

        Route::resource('challenge-type', ChallengeTypeController::class);
        Route::resource('challenges', ChallengeController::class);
    });
});


//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
