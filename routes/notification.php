<?php

use App\Http\Controllers\V1\Panel\Cases\CaseReportSurveryFieldController;
use App\Http\Controllers\V1\Panel\Cases\CasePropertyController;
use App\Http\Controllers\V1\Panel\Cases\CaseReportCommentController;
use App\Http\Controllers\V1\Panel\Cases\CaseReportController;
use App\Http\Controllers\V1\Panel\Cases\CaseRequestController;
use App\Http\Controllers\V1\Panel\Cases\CaseResearchController;
use App\Http\Controllers\V1\Panel\Notifiacation\NotifiacationController;
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


 Route::get('notifications', [NotifiacationController::class,'index']);
 Route::get('notifications/{notification}', [NotifiacationController::class,'show']);


});








