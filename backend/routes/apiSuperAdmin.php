<?php

use App\Http\Controllers\SuperAdmin\AuthenticateController;
use App\Http\Controllers\SuperAdmin\RoleController;
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

Route::group(['middleware' => 'jwt_super_admin.auth'], function () {
    Route::get('me', [AuthenticateController::class, 'me'])->name('super-admin.me');
    Route::post('logout', [AuthenticateController::class, 'logout'])->name('super-admin.logout');
    Route::post('change-password', [AuthenticateController::class, 'changePassword'])->name('super-admin.change-password');
    Route::post('update', [AuthenticateController::class, 'update'])->name('super-admin.update');

    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', [RoleController::class, 'index'])->name('super-admin.role.all');
        Route::get('/{id}', [RoleController::class, 'show'])->name('super-admin.role.show');
        Route::post('/', [RoleController::class, 'store'])->name('super-admin.role.store');
        Route::put('/{id}', [RoleController::class, 'update'])->name('super-admin.role.update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('super-admin.role.destroy');
    });
});
