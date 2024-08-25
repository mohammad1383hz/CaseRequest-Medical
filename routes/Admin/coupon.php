<?php

use App\Http\Controllers\V1\Admin\Acl\PermissionController;
use App\Http\Controllers\V1\Admin\Acl\RoleController;
use App\Http\Controllers\V1\Admin\Coupon\CouponController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('coupons', CouponController::class);
    // Route::apiResource('permissions', PermissionController::class);
    // Route::post('give/role/{user}', [RoleController::class,'giveRole']);
    // Route::post('give/permission/{user}', [PermissionController::class,'givePermission']);
// });


