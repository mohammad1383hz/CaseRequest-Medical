<?php

use App\Http\Controllers\V1\Admin\Cases\CaseCategoryController;
use App\Http\Controllers\V1\Auth\AdminLoginController;
use App\Http\Controllers\V1\Auth\LoginController;
use App\Http\Controllers\V1\Auth\ResetPasswordController;
use App\Http\Controllers\V1\Auth\VerifyController;
use App\Http\Controllers\V1\User\UserController;
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
// Route::middleware(['web'])->group(function () {
    Route::post('admin/login', [AdminLoginController::class,'login']);

 Route::post('login', [LoginController::class,'login'])->name('login');
 Route::post('login/otp', [LoginController::class,'loginOtp'])->name('login.otp');
 Route::post('check/otp', [LoginController::class,'checkotp'])->name('check.otp');

 Route::post('check/phone', [ResetPasswordController::class,'checkPhone']);
 Route::post('reset/password', [ResetPasswordController::class,'resetPassword']);

 
// });

//  Route::put('users/update/profile', [UserController::class,'update']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [LoginController::class,'logout']);
    Route::post('fcm/token', [LoginController::class,'updateFcm']);
    Route::post('request/verify/mail', [VerifyController::class,'requestVerify']);

    
});