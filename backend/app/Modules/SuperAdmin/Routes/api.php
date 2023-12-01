<?php

use App\Modules\SuperAdmin\Controllers\RoleController;
use App\Modules\SuperAdmin\Controllers\AuthenticateController;
use App\Modules\SuperAdmin\Controllers\PermissionController;
use App\Modules\SuperAdmin\Controllers\AdminManagementController;
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

Route::group(['prefix' => 'super-admin'], function () {
    Route::post('/login', [AuthenticateController::class, 'login']);

    Route::group(['middleware' => 'jwt_super_admin.auth'], function () {
        Route::get('me', [AuthenticateController::class, 'me'])->name('super-admin.me');
        Route::post('logout', [AuthenticateController::class, 'logout'])->name('super-admin.logout');
        Route::post('change-password', [AuthenticateController::class, 'changePassword'])->name('super-admin.change-password');
        Route::post('update', [AuthenticateController::class, 'update'])->name('super-admin.update');
        Route::post('create-admin', [AdminManagementController::class, 'createAdmin'])->name('super-admin.create-admin');
        Route::get('list-admin', [AdminManagementController::class, 'listAdmin'])->name('super-admin.list-admin');
        Route::get('/admins/{id}', [AdminManagementController::class, 'detail'])->name('super-admin.detail');
        Route::patch('block-admin', [AdminManagementController::class, 'block'])->name('supper-admin.block-admin');
        Route::patch('unblock-admin', [AdminManagementController::class, 'unblock'])->name('supper-admin.unblock-admin');
        Route::delete('admins/{id}', [AdminManagementController::class, 'deleteAdmin'])->name('super-admin.delete-admin');
        Route::put('admins/{id}', [AdminManagementController::class, 'editAdmin'])->name('super-admin.edit-admin');

        Route::group(['prefix' => 'roles'], function () {
            Route::get('/', [RoleController::class, 'index'])->name('super-admin.role.all');
            Route::get('/{id}', [RoleController::class, 'show'])->name('super-admin.role.show');
            Route::post('/', [RoleController::class, 'store'])->name('super-admin.role.store');
            Route::put('/{id}', [RoleController::class, 'update'])->name('super-admin.role.update');
            Route::delete('/{id}', [RoleController::class, 'destroy'])->name('super-admin.role.destroy');
        });

        Route::group(['prefix' => 'permissions'], function () {
            Route::get('/', [PermissionController::class, 'index'])->name('super-admin.permission.all');
            Route::post('/without', [PermissionController::class, 'getPermissionWithout'])->name('super-admin.permission.without');
        });
    });
});

Route::pattern('id', '\d+');
