<?php

namespace App\Http\Controllers\V1\Admin\Finance;
use App\Http\Controllers\Controller;

use App\Http\Resources\Admin\CouponCollection;
use App\Http\Resources\Admin\CouponResource;

use App\Http\Resources\Admin\FinancialAccountCollection;
use App\Http\Resources\Admin\FinancialAccountResource;
use App\Models\FinancialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class FinancialAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:financialaccount.index'])->only('index');
        $this->middleware(['permission:financialaccount.store'])->only('store');
        $this->middleware(['permission:financialaccount.show'])->only('show');
        $this->middleware(['permission:financialaccount.update'])->only('update');
        $this->middleware(['permission:financialaccount.destroy'])->only('destroy');

    }
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
        $sortBy = $request->query('sort_by', 'id'); // Default to sorting by 'id' if not specified
        $financialAccounts = FinancialAccount::orderBy($sortBy, 'desc')->paginate($perPage);
        return new FinancialAccountCollection($financialAccounts);
    }
    public function show(FinancialAccount $account){
        return new FinancialAccountResource($account);

    }

    public function store(Request $request){
      
        try {
            $validated = $request->validate([
                'user_id' => 'nullable|integer|exists:users,id',
                'payment_geteway_id'=> 'nullable',
            'account_type'=> 'required|in:user,wallet,gateway,user_bank',
            'name'=> 'required',
            'description'=> 'nullable',
            'card_number'=> 'nullable',
            'ibank'=> 'nullable',
            'bank'=> 'nullable',
            'account_number'=> 'nullable',
               
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $financialAccount=FinancialAccount::create([
            'user_id'=>$request['user_id'],
            'payment_geteway_id'=>$request['payment_geteway_id'],
            'account_type'=>$request['account_type'],
            'name'=>$request['name'],
            'description'=>$request['description'],
            'card_number'=>$request['card_number'],
            'ibank'=>$request['ibank'],
            'bank'=>$request['bank'],
            'account_number'=>$request['account_number']
        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$financialAccount]], 200);
    }

    public function update(Request $request,FinancialAccount $account){
        try {
            $validated = $request->validate([
                'user_id' => 'nullable|integer|exists:users,id',
                'payment_geteway_id'=> 'nullable',
            'account_type'=> 'required|in:user,wallet,gateway,user_bank',
            'name'=> 'required',
            'description'=> 'nullable',
            'card_number'=> 'nullable',
            'ibank'=> 'nullable',
            'bank'=> 'nullable',
            'account_number'=> 'nullable',
               
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $account->update([
            'user_id'=>$request['user_id'],
            'payment_geteway_id'=>$request['payment_geteway_id'],
            'account_type'=>$request['account_type'],
            'name'=>$request['name'],
            'description'=>$request['description'],
            'card_number'=>$request['card_number'],
            'ibank'=>$request['ibank'],
            'bank'=>$request['bank'],
            'account_number'=>$request['account_number']
        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$account]], 200);
    }
    public function destroy(FinancialAccount $account){
        $account->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }
    }







