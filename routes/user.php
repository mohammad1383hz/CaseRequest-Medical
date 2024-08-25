<?php

use App\Http\Controllers\V1\Admin\Cases\CaseCategoryController;
use App\Http\Controllers\V1\Auth\VerifyController;
use App\Http\Controllers\V1\Notification\SmsController;
use App\Http\Controllers\V1\Panel\User\UserController;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Verified;
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
Route::middleware('auth:sanctum')->group(function () {
    Route::get('users/show', [UserController::class,'show']);

    Route::put('users/update/profile', [UserController::class,'update']);
   
    Route::post('users/uploar/image/profile', [UserController::class,'uploadProfile']);



    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->middleware('auth')->name('verification.notice');
   
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
     
        return back()->with('message', 'Verification link sent!');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');
});
    Route::get('/email/verify/{id}/{hash}', [VerifyController::class,'verify'])->middleware(['signed'])->name('verification.verify');
       
   


Route::post('users/register', [UserController::class,'register']);
Route::post('users/register/check/otp', [UserController::class,'checkOtp']);
Route::post('send', [SmsController::class,'send']);


