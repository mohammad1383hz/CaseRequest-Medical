<?php

namespace App\Http\Controllers\V1\Expert\Finance;
use App\Http\Controllers\Controller;
use App\Models\CaseAssignment;
use App\Models\CaseReport;
use App\Models\CaseReportComment;
use App\Models\CaseReportSurvery;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\CaseRequest;
use App\Models\CurrencyConversionRate;
use App\Models\FinancialAccount;
use App\Models\FinancialDocument;
use App\Models\WithdrawRequest;;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class WalletController extends Controller
{
    // public function inventory(Request $request){

    //     $user_id=$request->user()->id;
    //     $financialAccountWalletId=FinancialAccount::where('user_id',$user_id)->where('account_type','wallet')->first()->id;
    //     $creditoAmount = FinancialDocument::where('creditor_id', $financialAccountWalletId)->sum('price');
    //     $debtorAmount = FinancialDocument::where('debtor_id', $financialAccountWalletId)->sum('price');

    //     $inventoryWallet=$debtorAmount-$creditoAmount;
    //     return response()->json(['success' => true, 'message' => 'true','data'=>$inventoryWallet], 200);
    // }
    public function __construct()
    {
        $this->middleware('role.expert_or_admin');
    }
    public function withdrawRequest(Request $request){
        try {
            $validated = $request->validate([
                'financial_account_id'=> 'required',
                'description'=> 'required',
                'date'=> 'required',
               
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $user_id=$request->user()->id;
        $user=User::find($user_id);
        $dataInventory=$this->inventory($request);
        $responseData = $dataInventory->getData();
        $price= $request['price'];
       
        if($user->currency_id != 1){
            $currencyConversionRate= CurrencyConversionRate::where("currency_id",$user->currency_id)->first();
             $rate=$currencyConversionRate->rate;    
             $min=17*$rate;
             if($price < $min){
                return response()->json(['success' => true, 'message' => 'min price validate','data'=>$min], 200);
            }
         }
         if($user->currency_id == 1){
            if($price < 17){
                return response()->json(['success' => true, 'message' => 'min price validate','data'=>17], 200);
            }
         }
        $inventoryWallet = $responseData->data;
        if($price > $inventoryWallet){
            return response()->json(['success' => true, 'message' => 'not inventory','data'=>null], 200);
        }
      
        $date = Carbon::now();
        $withdrawRequest=WithdrawRequest::create([
            'user_id'=>$user_id,
            'financial_account_id'=>$request['financial_account_id'],
            'status'=>'requested',
            'price'=>$price,
            'description'=>$request['description'],
            'date'=>$date,
        ]);
        return response()->json(['success' => true, 'message' => 'true','data'=>[$withdrawRequest]], 200);

        //get CaseRequest user auth
    }



}







