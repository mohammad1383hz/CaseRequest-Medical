<?php

use App\Http\Controllers\V1\Admin\Cases\CaseCategoryController;
use App\Http\Controllers\V1\Admin\Countries\CountryController;
use App\Http\Controllers\V1\Admin\File\FileManagerController;
use App\Http\Controllers\V1\Panel\Currency\CurrencyController;
use App\Http\Controllers\V1\Panel\User\UserController;
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
// Route::apiResource('countries', CountryController::class);


Route::get('files', [FileManagerController::class,'index']);
Route::post('files/cut', [FileManagerController::class,'cut']);
Route::post('files/copy', [FileManagerController::class,'copy']);
Route::post('files/delete', [FileManagerController::class,'delete']);

Route::post('files/rename', [FileManagerController::class,'rename']);
Route::post('files/new/directory', [FileManagerController::class,'newDirectory']);
Route::post('files/upload', [FileManagerController::class,'upload']);
