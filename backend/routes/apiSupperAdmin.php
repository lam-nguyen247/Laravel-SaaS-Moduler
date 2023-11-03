<?php

use App\Http\Controllers\SupperAdmin\AuthenticateController;
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

Route::post('/login', [AuthenticateController::class, 'login']);

Route::group(['middleware' => 'jwt_supper_admin.auth'], function () {
    Route::get('me', [AuthenticateController::class, 'me'])->name('supper-admin.me');
    Route::post('logout', [AuthenticateController::class, 'logout'])->name('supper-admin.logout');
    Route::post('change-password', [AuthenticateController::class, 'changePassword'])->name('supper-admin.change-password');
    Route::post('update', [AuthenticateController::class, 'update'])->name('supper-admin.update');
});