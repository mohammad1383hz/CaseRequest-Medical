<?php

namespace App\Http\Controllers\V1\Admin\Finance;
use App\Http\Controllers\Controller;

use App\Http\Resources\Admin\CouponCollection;
use App\Http\Resources\Admin\CouponResource;

use App\Http\Resources\Admin\InvoiceCollection;
use App\Http\Resources\Admin\InvoiceResource;
use App\Http\Resources\Panel\FinancialAccountCollection;
use App\Http\Resources\Panel\FinancialAccountResource;
use App\Models\FinancialAccount;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:invoice.index'])->only('index');
        $this->middleware(['permission:invoice.store'])->only('store');
        $this->middleware(['permission:invoice.show'])->only('show');
        $this->middleware(['permission:invoice.update'])->only('update');
        $this->middleware(['permission:invoice.destroy'])->only('destroy');

    }
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
        $invoices = Invoice::paginate($perPage);
        return new InvoiceCollection($invoices);
    }
public function show(Invoice $invoice){
    return new InvoiceResource($invoice);

}

    public function store(Request $request){
        // $validated = $request->validate([
        //     'user_id'=> 'required',
        //     'payment_geteway_id'=> 'required',
        //     'type'=> 'required',
        //     'name'=> 'required',
        //     'description'=> 'required',
        //     'card_number'=> 'required',
        //     'ibank'=> 'required',
        //     'bank'=> 'required',
        //     'account_number'=> 'required',

        // ]);
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',

                'currency_id'=>'required|integer',
                'status'=>'required',
                'date'=>'required',
                'description'=>'required',
                'is_payed'=>'required',
                'file_id'=>'nullable|integer|exists:files,id',
                'total_items'=>'nullable',
                'total_discount_items'=>'nullable',
                'total_discount'=>'nullable',
                'invoice_payable'=>'required',
                'invoice_number'=>'nullable',
                'first_name'=>'required',
                'last_name'=>'required',
                'qr_code_link'=>'nullable',
               
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $invoice=Invoice::create([
            'user_id'=>$request['user_id'],
            'currency_id'=>$request['currency_id'],
            'status'=>$request['status'],
            'date'=>$request['date'],
            'description'=>$request['user_id'],
            'is_payed'=>$request['is_payed'],
            'file_id'=>$request['file_id'],
            'total_items'=>$request['total_items'],
            'total_discount_items'=>$request['total_discount_items'],
            'total_discount'=>$request['total_discount'],
            'invoice_payable'=>$request['invoice_payable'],
            'invoice_number'=>$request['invoice_number'],
            'first_name'=>$request['first_name'],
            'last_name'=>$request['last_name'],
            'qr_code_link'=>$request['qr_code_link'],

            'rate'=>$request['rate'],

        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$invoice]], 200);
    }

    public function update(Request $request,Invoice $invoice){
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',

                'currency_id'=>'required|integer',
                'status'=>'required',
                'date'=>'required',
                'description'=>'required',
                'is_payed'=>'required',
                'file_id'=>'nullable|integer|exists:files,id',
                'total_items'=>'nullable',
                'total_discount_items'=>'nullable',
                'total_discount'=>'nullable',
                'invoice_payable'=>'required',
                'invoice_number'=>'nullable',
                'first_name'=>'required',
                'last_name'=>'required',
                'qr_code_link'=>'nullable',
               
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $invoice->update([
            'user_id'=>$request['user_id'],
            'currency_id'=>$request['currency_id'],
            'status'=>$request['status'],
            'date'=>$request['date'],
            'description'=>$request['user_id'],
            'is_payed'=>$request['is_payed'],
            'file_id'=>$request['file_id'],
            'total_items'=>$request['total_items'],
            'total_discount_items'=>$request['total_discount_items'],
            'total_discount'=>$request['total_discount'],
            'invoice_payable'=>$request['invoice_payable'],
            'invoice_number'=>$request['invoice_number'],
            'first_name'=>$request['first_name'],
            'qr_code_link'=>$request['qr_code_link'],

            'last_name'=>$request['last_name'],
            'rate'=>$request['rate'],

        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$invoice]], 200);
    }
    public function destroy(Invoice $invoice){
        $invoice->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }
    }







