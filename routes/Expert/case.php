<?php

use App\Http\Controllers\V1\Admin\Acl\PermissionController;
use App\Http\Controllers\V1\Admin\Acl\RoleController;
use App\Http\Controllers\V1\Expert\Cases\CaseAssignController;
use App\Http\Controllers\V1\Expert\Cases\CaseCommentController;
use App\Http\Controllers\V1\Expert\Cases\CaseReportController;
use App\Http\Controllers\V1\Expert\Cases\CaseRequestController;
use App\Http\Controllers\V1\Expert\Cases\CaseVilolationController;
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
    Route::get('get/case/request', [CaseRequestController::class,'index']);
    Route::put('change/status/request/{caseRequest}', [CaseRequestController::class,'statusUpdateCaseRequest']);
    Route::put('complate/status/request/{caseRequest}', [CaseRequestController::class,'complateCaseRequest']);





    Route::get('get/case/assignment', [CaseAssignController::class,'index']);
    Route::post('assign/case/{caseRequest}', [CaseAssignController::class,'assignCase']);

    Route::get('case/report/me', [CaseReportController::class,'index']);
    Route::post('case/report', [CaseReportController::class,'makeCaseReport']);
    Route::put('case/score/{caseReport}', [CaseReportController::class,'scoreCase']);

    // Route::apiResource('violations', CaseVilolationController::class);
    Route::get('case/violations', [CaseVilolationController::class,'index']);
    Route::get('case/violations/{caseViolation}', [CaseVilolationController::class,'show']);
    Route::put('case/violations/{caseViolation}', [CaseVilolationController::class,'update']);
    Route::post('case/violations', [CaseVilolationController::class,'store']);
    Route::delete('case/violations/{caseViolation}', [CaseVilolationController::class,'delete']);


    Route::get('case/comments', [CaseCommentController::class,'index']);

    Route::put('case/comments/{caseReportComment}', [CaseCommentController::class,'update']);
    Route::post('case/comments', [CaseCommentController::class,'replyComment']);


    
});






