<?php

use App\Http\Controllers\V1\Panel\Cases\CaseReportSurveryFieldController;
use App\Http\Controllers\V1\Panel\Cases\CasePropertyController;
use App\Http\Controllers\V1\Panel\Cases\CaseReportCommentController;
use App\Http\Controllers\V1\Panel\Cases\CaseReportController;
use App\Http\Controllers\V1\Panel\Cases\CaseRequestController;
use App\Http\Controllers\V1\Panel\Finance\CouponController;
use App\Http\Controllers\V1\Panel\Finance\FinancialAccountController;
use App\Http\Controllers\V1\Panel\Finance\FinancialDocumentController;
use App\Http\Controllers\V1\Panel\Finance\InvoiceController;
use App\Http\Controllers\V1\Panel\Finance\WalletController;
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
Route::middleware(['auth:sanctum','check.user.active'])->group(function () {

Route::post('check/coopon', [CouponController::class,'checkCoupon']);
Route::get('invoices', [InvoiceController::class,'index']);
Route::get('invoices/{invoice}', [InvoiceController::class,'show']);
Route::get('finance/documents', [FinancialDocumentController::class,'index']);
Route::get('finance/documents/{financialDocument}', [FinancialDocumentController::class,'show']);
Route::post('invoices', [InvoiceController::class,'store'])->name('invoice.store');
Route::get('get/pay', [InvoiceController::class,'getPayLink'])->name('invoice.getPayLink');


Route::get('finance/accounts', [FinancialAccountController::class,'index']);
Route::get('finance/accounts/{account}', [FinancialAccountController::class,'show']);
Route::post('finance/accounts', [FinancialAccountController::class,'store']);
Route::put('finance/accounts/{account}', [FinancialAccountController::class,'update']);
Route::delete('finance/accounts/{account}', [FinancialAccountController::class,'destroy']);



Route::get('wallet/inventory', [WalletController::class,'inventory']);
Route::post('wallet/pay', [WalletController::class,'payWithWallet']);
Route::post('wallet/chargingWallet', [WalletController::class,'chargingWallet'])->name('wallet.charging');

Route::post('change/wallet', [WalletController::class,'changeCurrency']);


});

// Route::post('callback/zarinpal', [PaymentController::class,'callback'])->name('payment.callback');









