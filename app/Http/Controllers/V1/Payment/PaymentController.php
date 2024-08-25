<?php

namespace App\Http\Controllers\V1\Payment;
use App\Http\Controllers\Controller;
use App\Http\Controllers\V1\Panel\Finance\InvoiceController;
use App\Models\FinancialAccount;
use App\Models\FinancialDocument;
use App\Models\Invoice as ModelsInvoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public static function pay($caseinvoice){


       $MERCHANT_ID =env('MERCHANT_ID','8d67a150-3bd6-4195-8922-03a22cd5e685');





       $caseinvoiceModel=ModelsInvoice::where('id',$caseinvoice)->first();


// dd($price);
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api.zarinpal.com/pg/v4/payment/request.json', [
            'merchant_id' => '8d67a150-3bd6-4195-8922-03a22cd5e685',
            'amount' => $caseinvoice->invoice_payable,
            'callback_url' => 'http://localhost:8000/api/callback/zarinpal',
            'description' => 'افزایش اعتبار کاربر شماره ۱۱۳۴۶۲۹',
            // 'metadata' => [
            //     'mobile' => '09121234567',
            //     'email' => 'info.test@gmail.com'
            // ],
        ]);
        $responseData = $response->json();
        // dd($responseData);
        // return $responseData;

        $caseinvoiceModel->update([
            'invoice_number_authority'=>$response['data']["authority"]
        ]);

        $payRout='https://www.zarinpal.com/pg/StartPay/' . $response['data']["authority"];


        return $payRout;




        // $invoice = (new Invoice)->amount($price);
        // return Payment::callbackUrl(route('payment.callback'))->purchase($invoice, function($driver, $transactionId) use ($caseinvoice) {
      

        //     })->pay()->render();
//

    }
    public static function callback(Request $request){
        $transaction_id=$request->Authority;
        $invoice=ModelsInvoice::where('invoice_number_authority',$transaction_id)->first();


        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post('https://api.zarinpal.com/pg/v4/payment/verify.json', [
            'merchant_id' => env('MERCHANT_ID'),
            'amount' => $invoice->invoice_payable,
            'authority' => $transaction_id,
        ]);
        
        $responseData = $response->json();
        
        // Check if the 'data' key exists in the response
        if (isset($responseData['data'])) {
            $data = $responseData['data'];
        
            // Check if the 'code' key exists in the 'data' array
            if (isset($data['code']) && $data['code'] == 100) {
                // 'code' is present and equals 100, perform the necessary action
                InvoiceController::updateInvoice($request, $invoice->id,'successful');
                return redirect('http://localhost:3000/checkout?status=successful');

            } else {
                // 'code' is either not present or not equal to 100, handle the error
                InvoiceController::updateInvoice($request, $invoice->id,'failed');
                return redirect('http://localhost:3000/checkout?status=successful');

            }
        } else {
            // 'data' key is not present in the response, handle the unexpected response format
            return response()->json(['success' => false, 'message' => 'Unexpected response format', 'data' => null], 200);
        }
        


    }








    public static function payForWallet($price,$financialDocument){




        $MERCHANT_ID =env('MERCHANT_ID','8d67a150-3bd6-4195-8922-03a22cd5e685');


        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api.zarinpal.com/pg/v4/payment/request.json', [
            'merchant_id' => '8d67a150-3bd6-4195-8922-03a22cd5e685',
            'amount' => $price,
            'callback_url' => 'http://localhost:8000/api/callback/zarinpal/wallet',
            'description' => 'افزایش اعتبار کاربر شماره ۱۱۳۴۶۲۹',
            // 'metadata' => [
            //     'mobile' => '09121234567',
            //     'email' => 'info.test@gmail.com'
            // ],
        ]);
        $responseData = $response->json();
        // return $responseData;
        $financialDocument=FinancialDocument::where('id',$financialDocument)->first();
        $financialDocument->update([
            'tracking_code'=>$response['data']["authority"]
        ]);
        $payRout='https://www.zarinpal.com/pg/StartPay/' . $response['data']["authority"];


        return $payRout;















    }
    public static function callbacForkWallet(Request $request){
        $transaction_id=$request->Authority;
        $user_id=$request->user()->id;

        $financialDocument=FinancialDocument::where('tracking_code',$transaction_id)->first();



        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post('https://api.zarinpal.com/pg/v4/payment/verify.json', [
            'merchant_id' => env('MERCHANT_ID'),
            'amount' => $financialDocument->price,
            'authority' => $transaction_id,
        ]);



        $responseData = $response->json();
        
        // Check if the 'data' key exists in the response
        if (isset($responseData['data'])) {
            $data = $responseData['data'];
        
            // Check if the 'code' key exists in the 'data' array
            if (isset($data['code']) && $data['code'] == 100) {
                $financialDocument->update([
                    'status'=>'successful',
                ]);
                $user=User::find($user_id);

                $financialAccountWallet=FinancialAccount::where('user_id',$user_id)->where('account_type','wallet')->first();
               $financialAccountGateWay= FinancialAccount::where('account_type','gateway')->first();
            $tracking_code=$financialDocument->tracking_code;
            FinancialDocument::create([
                'creditor_id'=>$financialAccountGateWay->id,
                'debtor_id'=>$financialAccountWallet->id,
                // 'description'
                'tracking_code'=>$tracking_code,
                'status'=>'successful',
    
                'date'=>Carbon::now(),
                'price'=>$financialDocument->price,
                'currency_id'=>$user->currency_id,
                // 'invoice_id'=>$invoice->id,
            ]);
            } else {
                // 'code' is either not present or not equal to 100, handle the error
                return response()->json(['success' => false, 'message' => 'Verification failed', 'data' => null], 200);
            }
        } else {
            // 'data' key is not present in the response, handle the unexpected response format
            return response()->json(['success' => false, 'message' => 'Unexpected response format', 'data' => null], 200);
        }








            // return $response;
            if(isset($response['data']['code']) && $response['data']['code'] == 100) {
               
            }else{
            return response()->json(['success' => false, 'message' => 'false','data'=>null], 200);
            }
            // بررسی و پردازش پاسخ
            // if ($response->successful()) {
            //     $data = $response->json();
            //     // انجام عملیات مورد نیاز با داده‌های دریافتی
            //     return $data;
            // } else {
            //     // پردازش خطا در صورت وجود
            //     $error = $response->status();
            //     return "خطا در ارتباط با سرور: $error";
            // }



    }





    // public function payZarrinpall(){




    //     // Purchase the given invoice.



    // }
}
