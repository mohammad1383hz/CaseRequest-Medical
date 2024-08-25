<?php

use App\Http\Controllers\V1\Admin\Acl\RoleController;
use App\Http\Controllers\V1\Admin\Cases\CaseAssignmentController;
use App\Http\Controllers\V1\Admin\Cases\CaseCategoryAnimalController;
use App\Http\Controllers\V1\Admin\Cases\CaseCategoryController;
use App\Http\Controllers\V1\Admin\Cases\CaseCategoryExpertCommissionController;
use App\Http\Controllers\V1\Admin\Cases\CaseCategoryExpertController;
use App\Http\Controllers\V1\Admin\Cases\CaseCategoryFiledController;
use App\Http\Controllers\V1\Admin\Cases\CaseGroupController;
use App\Http\Controllers\V1\Admin\Cases\CaseReportController;
use App\Http\Controllers\V1\Admin\Cases\CaseRequestController;
use App\Http\Controllers\V1\Admin\Cases\CaseViolationController;
use App\Http\Controllers\V1\Admin\Currency\CurrencyController;
use App\Http\Controllers\V1\Admin\Currency\CurrencyConversionController;
use App\Http\Controllers\V1\Admin\Notification\NotificationController;
use App\Http\Controllers\V1\Admin\Notification\StatusNotificationController;
use App\Http\Resources\Admin\StatusNotificationCollection;
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
 
//     });


    Route::post('send/notifications', [NotificationController::class,'send']);

    Route::get('status/notifications', [StatusNotificationController::class,'index']);
    Route::get('status/notifications/{statusNotification}', [StatusNotificationController::class,'show']);
    Route::put('status/notifications/{statusNotification}', [StatusNotificationController::class,'update']);

    // Route::apiResource('currencyConversions', CurrencyConversionController::class);

