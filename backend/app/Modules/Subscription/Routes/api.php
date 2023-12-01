<?php

use App\Modules\Subscription\Controllers\SubscriptionController;
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

Route::group(['middleware' => 'jwt_user.auth'], function () {
    Route::post('subscription', [SubscriptionController::class, 'store']);
    Route::get('subscription', [SubscriptionController::class, 'index']);
});
