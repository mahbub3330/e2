<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\Challenge\ChallengeController;
use App\Http\Controllers\Challenge\ChallengeTypeController;
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


    Route::group(['middleware' => ['auth:api']], function(){
        Route::resource('users', UserController::class)->except('store');

        Route::resource('teams', TeamController::class);
        Route::post('team-login',[TeamController::class, 'login']);

        Route::resource('challenge-type', ChallengeTypeController::class);
        Route::resource('challenges', ChallengeController::class);
        //post.(/challenges)
        //put.(/challenges/{id}
        //delete(/challenges/{id}
        //get(/challenges/{id})
        //get(/challenges)
        //[{id:1, text: ''}]
    });
});


//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
