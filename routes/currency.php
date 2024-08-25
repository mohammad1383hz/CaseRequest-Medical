<?php

use App\Http\Controllers\V1\Admin\Cases\CaseCategoryController;
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
// Route::middleware(['auth:sanctum','check.user.active'])
Route::get('currenies', [CurrencyController::class,'index']);



