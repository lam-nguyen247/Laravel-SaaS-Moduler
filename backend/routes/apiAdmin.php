<?php

use App\Http\Controllers\Admin\AuthenticateController;
use App\Http\Controllers\Admin\UserController;
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

Route::post('auth/login', [AuthenticateController::class, 'login']);

Route::group(['middleware' => 'jwt_admin.auth'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::get('me', [AuthenticateController::class, 'me']);
        Route::post('logout', [AuthenticateController::class, 'logout']);
        Route::post('change-password', [AuthenticateController::class, 'changePassword']);
        Route::post('change-profile', [AuthenticateController::class, 'changeProfile']);
    });

    Route::resource('users', UserController::class);
});
