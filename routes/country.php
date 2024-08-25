<?php

use App\Http\Controllers\V1\Admin\Cases\CaseCategoryController;
use App\Http\Controllers\V1\Countries\CountryController;
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
// Route::get('countries/{calling_code}', [CountryController::class,'show']);

Route::get('countries/', [CountryController::class,'index']);


