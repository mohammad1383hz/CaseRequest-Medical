<?php

use App\Http\Controllers\V1\Admin\Acl\PermissionController;
use App\Http\Controllers\V1\Admin\Acl\RoleController;
use App\Http\Controllers\V1\Expert\Cases\CaseAssignController;
use App\Http\Controllers\V1\Expert\Cases\CaseCommentController;
use App\Http\Controllers\V1\Expert\Cases\CaseReportController;
use App\Http\Controllers\V1\Expert\Cases\CaseRequestController;
use App\Http\Controllers\V1\Expert\Cases\CaseVilolationController;
use App\Http\Controllers\V1\Expert\Finance\FinanceAccountController;
use App\Http\Controllers\V1\Expert\Finance\WalletController;
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

    Route::apiResource('financeAcounts', FinanceAccountController::class);
    // Route::get('get/inventory', [WalletController::class,'inventory']);
    Route::post('request/withdraw', [WalletController::class,'withdrawRequest']);






});

