<?php

namespace App\Http\Controllers\V1\Panel\Finance;
use App\Http\Controllers\Controller;

use App\Http\Controllers\V1\Payment\PaymentController;


use App\Http\Resources\Panel\InvoiceCollection;
use App\Http\Resources\Panel\InvoiceResource;

use App\Models\CaseRequest;
use App\Models\Coupon;
use App\Models\CurrencyConversionRate;
use App\Models\FinancialAccount;
use App\Models\FinancialDocument;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InvoiceController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:financialaccount.index'])->only('index');
        // $this->middleware(['permission:financialaccount.store'])->only('store');
        // $this->middleware(['permission:financialaccount.show'])->only('show');
        // $this->middleware(['permission:financialaccount.update'])->only('update');
        // $this->middleware(['permission:financialaccount.destroy'])->only('destroy');

    }
   
public function index(Request $request)
{
    $user_id = $request->user()->id;
    $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified

    $invoices = Invoice::where('user_id', $user_id)->paginate($perPage);

    return new InvoiceCollection($invoices);
}
    public function show(Invoice $invoice){
        return new InvoiceResource($invoice);

    }

    public function store(Request $request){
        try {
            $validated = $request->validate([               
                'description'=> 'nullable',
                'coupon_code'=> 'nullable',
                'case_request_id'=> 'required',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
       $user_id=$request->user()->id;
    //    $user_id=1;
       $user=User::find($user_id);
       $caseRequest=CaseRequest::where('id', $request['case_request_id'])->first();
    //    $caseRequest=CaseRequest::where('id', 2)->first();


       $caseRequestPrice=$caseRequest->getTotalPrice();
       $responseCoupon=CouponController::checkCoupon($request);
       $currency_user=User::find($request->user()->id)->currency_id;

       if($currency_user != 1) {
        $currencyConversionRate= CurrencyConversionRate::where("currency_id",$currency_user)->first();
        $rate=$currencyConversionRate->rate;    
        $caseRequestPrice = $caseRequestPrice*$rate;
    }
       $responseCoupon = $responseCoupon->getData();
       $price = $responseCoupon->data;
    //    dd($price);
       if($caseRequestPrice != $price){
        $coupon=Coupon::where('code',$request['coupon_code'])->first();
        $coupon->increment('use_count', 1);
       }
    //    $price = 5000;


        $currency=$user->currency;

    

        $invoice=Invoice::create([
            'user_id'=>$user_id,
            'currency_id'=>$user->currency_id,
            'status'=>'created',
            'date'=>Carbon::now(),
            'description'=>$request['description'],
            'is_payed'=>false,
            'total_discount'=>$caseRequestPrice-$price,
            'invoice_payable'=>$price,
            'first_name'=>$user->first_name,
            'last_name'=>$user->last_name,
        ]);
        $jDate = \Morilog\Jalali\Jalalian::fromCarbon(Carbon::now());
        $date=$jDate->format('Ymd');
        $date .= $invoice->id;
        // dd($date);
    $invoice->update([
        'invoice_number'=>$date
    ]);
        DB::table('invoice_items')->insert([
            'invoice_id' => $invoice->id,
            'case_request_id' =>2,
        ]);
    //     $response=PaymentController::pay($price,$invoice->id);
    // //    dd($response);
    //     return $response;
     


    }

    public static function updateInvoice(Request $request,$invoice,$status){
        // $user_id=$request->user()->id;
        $invoice=Invoice::where('id',$invoice)->first();
        $user=User::find($invoice->user_id);
        $user_id=$invoice->user_id;
        if($status== 'successful'){
        $invoice->update(['status'=>'registered']);
        $case_request_id= $invoice->invoiceItem->case_request_id;
        CaseRequest::where('id',$case_request_id)->update(['status'=> 'submitted']);
        // submitted
        }
        // if($status== 'successful'){
        //     $invoice->update(['status'=>'registered']);
        //     }
        $financialAccountUser=FinancialAccount::where('user_id',$user_id)->where('account_type','user')->first();
        $financialAccountWallet=FinancialAccount::where('user_id',$user_id)->where('account_type','wallet')->first();

       $financialAccountGateWay= FinancialAccount::where('account_type','gateway')->first();
        $financialAccountApp=FinancialAccount::where('account_type','app')->first();


        FinancialDocument::create([
        'creditor_id'=>$financialAccountUser->id,
        'debtor_id'=>$financialAccountGateWay->id,
        // 'description'
        'tracking_code'=>$invoice->invoice_number,
        'date'=>Carbon::now(),
        'status'=>$status,
        'price'=>$invoice->invoice_payable,
        'currency_id'=>$user->currency_id,
        'invoice_id'=>$invoice->id,
    ]);
    FinancialDocument::create([
        'creditor_id'=>$financialAccountGateWay->id,
        'debtor_id'=>$financialAccountWallet->id,
        // 'description'
        'tracking_code'=>$invoice->invoice_number,
        'status'=>$status,
        'date'=>Carbon::now(),
        'price'=>$invoice->invoice_payable,
        'currency_id'=>$user->currency_id,
        'invoice_id'=>$invoice->id,
    ]);
    FinancialDocument::create([
        'creditor_id'=>$financialAccountWallet->id,
        'debtor_id'=>$financialAccountApp->id,
        // 'description'
        'tracking_code'=>$invoice->invoice_number,
        'status'=>$status,

        'date'=>Carbon::now(),
        'price'=>$invoice->invoice_payable,
        'currency_id'=>$user->currency_id,
        'invoice_id'=>$invoice->id,
    ]);
   
    return response()->json(['success' => true, 'message' => 'true','data'=>[$invoice]], 200);
    }



    public function getPayLink(Request $request){
        try {
            $validated = $request->validate([               
                'coupon_code'=> 'required',
                'invoice_id'=> 'required',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }

    //       $caseRequest=CaseRequest::where('id', $request['case_request_id'])->first();
    //    $caseRequestPrice=$caseRequest->getTotalPrice();
    //    $responseCoupon=CouponController::checkCoupon($request);
        $response=PaymentController::pay($request['invoice_id']);
        return $response;
    }















    }







