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
use App\Http\Controllers\V1\Admin\Finance\FinancialAccountController;
use App\Http\Controllers\V1\Admin\Finance\FinancialDocumentController;
use App\Http\Controllers\V1\Admin\Finance\InvoiceController;
use App\Http\Controllers\V1\Admin\Finance\ReportFinancialController;
use App\Http\Controllers\V1\Admin\Finance\WithdrawRequestController;
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


Route::middleware('auth:sanctum')->group(function () {
    // //Route::post('orders', [OrderController::class,'storeOrderByConsumer'])->name('order.store');
    // //Route::put('expert/orders/{order}', [OrderController::class,'updateOrderByExpert'])->name('order.update.expert');
    // //Route::put('orders/{order}', [OrderController::class,'updateOrderByConsumer'])->name('order.update.consumer');
    // //Route::post('orders/status', [OrderController::class,'updateStatus'])->name('order.update.status');
    // Route::get('orders', [OrderController::class,'getOrder'])->name('order.index');
    // Route::put('orders/{order}/updatestatus', [OrderController::class,'updateStatus'])->name('order.update.status');
    // Route::get('orders/getnumber', [OrderController::class,'getNumberOrderStatus'])->name('order.get.number.order');
    // Route::get('orders/showemployee/{order}', [OrderController::class,'showEmployee'])->name('order.showemployee');
    // Route::put('orders/selectemployee/{order}', [OrderController::class,'selectEmployee'])->name('order.selectemployee');
    // Route::post('orders/{user}', [OrderController::class,'store'])->name('order.store');
    // Route::post('orders/otherservice/{id}', [OrderController::class,'storeOther'])->name('order.store.other');
    // Route::put('orders/{order}', [OrderController::class,'update'])->name('order.update');
    // Route::put('orders/updateother/{order}', [OrderController::class,'updateOther'])->name('order.other.update');
    // Route::post('orders/{order}/document/upload/', [OrderDocumentController::class,'uploadFileDocumentOrder'])->name('order.upload.document');
    // Route::get('orders/{order}', [OrderController::class,'show'])->name('order.show');
    // //Route::get('orders/{employer}', [OrderController::class,'getOrderEmployer'])->name('order.employer');
    // Route::get('orders/user/{user}', [OrderController::class,'getOrderConsumer'])->name('order.consumer');
    // Route::post('checkuserorder', [OrderController::class,'checkUserOrder'])->name('order.checkuser');
    // Route::put('orders/{order}/updatestatusemployee', [OrderController::class,'updateStatusRelation'])->name('order.update.status.employee');
    // Route::post('orders/expert/manage/{order}', [OrderController::class,'manageExpertOrder'])->name('order.expert');
    // Route::post('orders/expert/change/{expert}/manage/{order}', [OrderController::class,'changeExpertOrder'])->name('order.expert.change');
    // Route::get('orders/expert/manage', [OrderController::class,'getOrderManageExpert'])->name('order.manage.expert');
    // Route::post('orders/clone/{order}', [OrderController::class,'cloneOrderByExpert'])->name('order.clone');
    // Route::put('orders/send/app/{order}', [OrderController::class,'sendOrderInApp'])->name('order.send.app');
    // Route::delete('orders/{order}', [OrderController::class,'delete'])->name('order.delete');
    // Route::post('orders/service/getnumber', [OrderController::class,'getNumberForService'])->name('order.number.service');

    });


    Route::apiResource('invoices', InvoiceController::class);
    Route::apiResource('documents', FinancialDocumentController::class);
    Route::apiResource('accounts', FinancialAccountController::class);

    Route::get('report/income', [ReportFinancialController::class,'reportFinanceIncome']);
    Route::get('report/category/income', [ReportFinancialController::class,'reportCategoryIncome']);

    
    Route::get('withdrawRequests', [WithdrawRequestController::class,'index']);
    Route::get('withdrawRequests/{withdrawRequest}', [WithdrawRequestController::class,'show']);
    Route::put('withdrawRequests/{withdrawRequest}', [WithdrawRequestController::class,'update']);
    Route::delete('withdrawRequests/{withdrawRequest}', [WithdrawRequestController::class,'destroy']);
    