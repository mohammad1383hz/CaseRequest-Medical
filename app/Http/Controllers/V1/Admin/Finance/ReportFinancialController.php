<?php

namespace App\Http\Controllers\V1\Admin\Finance;
use App\Http\Controllers\Controller;

use App\Http\Resources\Admin\CouponCollection;
use App\Http\Resources\Admin\CouponResource;

use App\Http\Resources\Admin\FinancialAccountCollection;
use App\Http\Resources\Admin\FinancialAccountResource;
use App\Models\CaseCategory;
use App\Models\FinancialAccount;
use App\Models\FinancialDocument;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class ReportFinancialController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:financialaccount.index'])->only('index');
        // $this->middleware(['permission:financialaccount.store'])->only('store');
        // $this->middleware(['permission:financialaccount.show'])->only('show');
        // $this->middleware(['permission:financialaccount.update'])->only('update');
        // $this->middleware(['permission:financialaccount.destroy'])->only('destroy');

    }
     public function reportFinanceIncome(){
        $financialAccountAppId=FinancialAccount::where('account_type','app')->first()->id;
        $creditoAmount = FinancialDocument::where('creditor_id', $financialAccountAppId)->sum('price');
        $debtorAmount = FinancialDocument::where('debtor_id', $financialAccountAppId)->sum('price');

        return response()->json(['success' => true, 'message' => 'true','data'=>['income'=>$debtorAmount,'checkout'=>$creditoAmount]], 200);
 
    }
    public function reportCategoryIncome(){
        $invoices=Invoice::all();
        $categories=CaseCategory::all();
      
        $categoriesArray = $categories->map(function ($category) {
            $caseRequests = $category->caseRequests;
            $totalValue = 0;
        
            foreach ($caseRequests as $caseRequest) {
                $invoiceItem = $caseRequest->invoiceItem;
                if ($invoiceItem) {
                    $invoice = $invoiceItem->invoice;
                    if ($invoice && $invoice->status == 'registered') {
                        $totalValue += $invoice->price;
                    }
                }
            }
        
            return [
                'id' => $category->id,
                'title' => $category->title,
                'value' => $totalValue,
            ];
        })->toArray();
        

      
        return response()->json(['success' => true, 'message' => 'true','data'=>$categoriesArray], 200);
 
    }
   

    }







