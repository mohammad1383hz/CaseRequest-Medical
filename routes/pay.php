<?php

use App\Http\Controllers\V1\Panel\Cases\CaseReportSurveryFieldController;
use App\Http\Controllers\V1\Panel\Cases\CasePropertyController;
use App\Http\Controllers\V1\Panel\Cases\CaseReportCommentController;
use App\Http\Controllers\V1\Panel\Cases\CaseReportController;
use App\Http\Controllers\V1\Panel\Cases\CaseRequestController;
use App\Http\Controllers\V1\Payment\PaymentController;
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
Route::post('pay/zarinpal', [PaymentController::class,'pay'])->name('payment');
Route::get('callback/zarinpal', [PaymentController::class,'callback'])->name('payment.callback');


Route::post('pay/zarinpal/wallet', [PaymentController::class,'payForWallet'])->name('payment.wallet');
Route::get('callback/zarinpal/wallet', [PaymentController::class,'callbacForkWallet'])->name('payment.callback.wallet');






