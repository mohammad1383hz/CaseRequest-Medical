<?php

namespace App\Http\Controllers\V1\Panel\Finance;
use App\Http\Controllers\Controller;

use App\Http\Controllers\V1\Payment\PaymentController;
use App\Http\Resources\Admin\CouponCollection;
use App\Http\Resources\Admin\CouponResource;

use App\Http\Resources\Panel\FinancialAccountCollection;
use App\Http\Resources\Panel\FinancialAccountResource;
use App\Models\CaseRequest;
use App\Models\Coupon;
use App\Models\FinancialAccount;
use App\Models\FinancialDocument;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class WalletController extends Controller
{
    public function __construct()
    {


    }
    public function inventory(Request $request){
        $user_id=$request->user()->id;
        $user=User::find($user_id);

        $financialAccountWalletId=FinancialAccount::where('user_id',$user_id)->where('account_type','wallet')->where('currency_id',$user->currency_id)->first()->id;
        $creditoAmount = FinancialDocument::where('creditor_id', $financialAccountWalletId)->where('status','successful')->sum('price');
        $debtorAmount = FinancialDocument::where('debtor_id', $financialAccountWalletId)->where('status','successful')->sum('price');

        $inventoryWallet=$debtorAmount-$creditoAmount;
        return response()->json(['success' => true, 'message' => 'true','data'=>$inventoryWallet], 200);


        //get CaseRequest user auth
    }

    public function payWithWallet(Request $request){
        try {
            $validated = $request->validate([
    
                
                
                
                'coupon_code'=> 'required',
                'case_request_id'=> 'required',
        
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $dataInventory=$this->inventory($request);
        $responseData = $dataInventory->getData();
    $inventoryWallet = $responseData->data;


        $user_id=$request->user()->id;
        $user=User::find($user_id);
        $caseRequest=CaseRequest::where('id', $request['case_request_id'])->first();
        $caseRequestPrice=$caseRequest->getTotalPrice();
        $responseCoupon=CouponController::checkCoupon($request);

        $responseCoupon = $responseCoupon->getData();
        $price = $responseCoupon->data;
    

        
        if ($inventoryWallet < $price) {
        return response()->json(['success' => false, 'message' => 'true','data'=>null], 200);

        }
        if($caseRequestPrice != $price){
            $coupon=Coupon::where('code',$request['coupon_code'])->first();
            $coupon->increment('use_count', 1);
           }

         $invoiceNumber = Str::random(15);

         $currency=$user->currency;
         if($currency->name!=='dollar'){
             $rate = $currency->conversionRate->rate;
             $price=$rate*$price;
             $caseRequestPrice=$rate*$caseRequestPrice;
         }

         $invoice=Invoice::create([
             'user_id'=>$user_id,
             'currency_id'=>$user->currency_id,
             'status'=>'created',
             'date'=>Carbon::now(),
             'description'=>$request['description'],
             'is_payed'=>true,
             'total_discount'=>$caseRequestPrice-$price,
             'invoice_payable'=>$price,
             'invoice_number'=>$invoiceNumber,
             'first_name'=>$user->first_name,
             'last_name'=>$user->last_name,
         ]);
         DB::table('invoice_items')->insert([
             'invoice_id' => $request['invoice_id'],
             'case_request_id' =>$request['case_request_id'],
         ]);

        $user_id=$request->user()->id;
        $user=User::find($user_id);
        $invoice->update(['status'=>'registered']);
        $financialAccountWallet=FinancialAccount::where('user_id',$user_id)->where('account_type','wallet')->first();

        $financialAccountApp=FinancialAccount::where('account_type','app')->first();


        FinancialDocument::create([
        'creditor_id'=>$financialAccountWallet->id,
        'debtor_id'=>$financialAccountApp->id,
        // 'description'
        // 'tracking_code'
        'date'=>Carbon::now(),
        'price'=>$invoice->invoice_payable,
        'currency_id'=>$user->currency_id,
        'invoice_id'=>$invoice->id,
            ]);
        //pay
        return response()->json(['success' => true, 'message' => 'true','data'=>[$invoice]], 200);
    }

    public function chargingWallet(Request $request){
        $price=$request['price'];

        $user_id=$request->user()->id;
        // $user_id=1;

        $user=User::find($user_id);
        $financialAccountUser=FinancialAccount::where('user_id',$user_id)->where('account_type','user')->first();
       $financialAccountGateWay= FinancialAccount::where('account_type','gateway')->first();
       $financialDocument= FinancialDocument::create([
        'creditor_id'=>$financialAccountUser->id,
        'debtor_id'=>$financialAccountGateWay->id,
        // 'description'
        // 'tracking_code'
        'date'=>Carbon::now(),
        'price'=>$price,
        'currency_id'=>$user->currency_id,
    ]);
        $response=PaymentController::payForWallet($price,$financialDocument->id);
        return $response;
      

    }

    public function changeCurrency(Request $request){
        $user_id=$request->user()->id;
        $user=User::find($user_id);
        $financialAccountWalletId=FinancialAccount::where('user_id',$user_id)->where('account_type','wallet')->where('currency_id',$request['currency_id'])->first();
        if(!$financialAccountWalletId){
              $financialAccountWalletId= FinancialAccount::create([
                'user_id'=>$user_id,
                'account_type'=>'wallet',
                'name'=>$user->last_name,
                'currency_id'=>$request['currency_id'],
            ]);
        }
        $user->update([
            'currency_id'=>$request['currency_id']
        ]);


        return response()->json(['success' => true, 'message' => 'true','data'=>[$financialAccountWalletId]], 200);

        //get CaseRequest user auth
    }

    

    }





