<?php

use App\Http\Controllers\V1\Panel\Cases\CaseReportSurveryFieldController;
use App\Http\Controllers\V1\Panel\Cases\CasePropertyController;
use App\Http\Controllers\V1\Panel\Cases\CaseReportCommentController;
use App\Http\Controllers\V1\Panel\Cases\CaseReportController;
use App\Http\Controllers\V1\Panel\Cases\CaseRequestController;
use App\Http\Controllers\V1\Panel\Cases\CaseResearchController;
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


 Route::get('getCaseGroupes', [CasePropertyController::class,'getCaseGroupes']);
 Route::get('getCaseCategoriesCaseGroupes/{caseGroup}', [CasePropertyController::class,'getCaseCategoriesCaseGroupes']);
 Route::get('getCaseCategoryAnimals/{caseCategory}', [CasePropertyController::class,'getCaseCategoryAnimals']);
 Route::get('getCaseCategoryExperts/{caseCategory}', [CasePropertyController::class,'getCaseCategoryExperts']);
 Route::get('getCaseCategoryFileds/{caseCategoryAnimal}', [CasePropertyController::class,'getCaseCategoryFileds']);



 Route::post('make/caseRequest', [CaseRequestController::class,'createCaseRequest']);
 Route::get('caseRequests/{caseRequest}', [CaseRequestController::class,'show']);




 Route::get('number/cases/waiting', [CaseRequestController::class,'numberCaseWatingUser']);


 Route::get('me', [CaseRequestController::class,'index']);
 Route::get('number/me', [CaseRequestController::class,'numberCaseUser']);



 Route::get('me/score', [CaseReportController::class,'myScore']);


 Route::put('give/score/report/{caseReport}', [CaseReportController::class,'giveScore']);

 Route::apiResource('casereportcomments', CaseReportCommentController::class);
 Route::put('refrence/{caseRequest}', [CaseRequestController::class,'cloneCaseRequest']);


 Route::get('surveryfields', [CaseReportSurveryFieldController::class,'index']);

 Route::post('make/surveryfields/{caseReport}', [CaseReportSurveryFieldController::class,'makeSurveryField']);






 Route::get('caseresearches', [CaseResearchController::class,'index']);
 Route::get('caseresearches/{caseResearch}', [CaseResearchController::class,'show']);
 Route::post('caseresearches', [CaseResearchController::class,'store']);
 Route::put('caseresearches/{caseResearch}', [CaseResearchController::class,'update']);
 Route::delete('caseresearches/{caseResearch}', [CaseResearchController::class,'delete']);


});








