<?php

use App\Modules\User\Controllers\AuthenticateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'user'], function () {
    Route::post('login', [AuthenticateController::class, 'login']);
    Route::post('signup', [AuthenticateController::class, 'signup']);
    Route::post('forgot', [AuthenticateController::class, 'forgotPassword']);
    Route::post('reset', [AuthenticateController::class, 'resetPassword']);

    Route::group(['middleware' => 'jwt_user.auth'], function () {
        Route::post('change-password', [AuthenticateController::class, 'changePassword']);
        Route::post('change-profile', [AuthenticateController::class, 'changeProfile']);
    });
});
