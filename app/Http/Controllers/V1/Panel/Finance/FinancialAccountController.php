<?php

namespace App\Http\Controllers\V1\Panel\Finance;
use App\Http\Controllers\Controller;
use App\Http\Resources\Expert\FinancialAccountCollection;
use App\Http\Resources\Expert\FinancialAccountResource;
use App\Models\CaseAssignment;
use App\Models\CaseReport;
use App\Models\CaseReportComment;
use App\Models\CaseReportSurvery;
use App\Models\FinancialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\CaseRequest;
use Illuminate\Validation\ValidationException;

;


class FinancialAccountController extends Controller
{
   
public function index(Request $request)
{
    $user_id = $request->user()->id;
    $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified

    $financialAccounts = FinancialAccount::where('user_id', $user_id)
                                            ->where('account_type', 'user_bank')
                                            ->paginate($perPage);

    return new FinancialAccountCollection($financialAccounts);
}
    public function show(FinancialAccount $account){
        return new FinancialAccountResource($account);

    }

public function store(Request $request){
    $user_id=$request->user()->id;
    try {
        $validated = $request->validate([

            'name'=> 'required',
            'description'=> 'required',
            'card_number'=> 'required',
            'ibank'=> 'required',
            'bank'=> 'required',
            'account_number'=> 'required',
    
      ]);
      } catch (ValidationException $e) {
          // Validation failed, return a JSON response with validation errors
          return response()->json(['errors' => $e->validator->errors()], 422);
      }

    $financialAccount=FinancialAccount::create([
        'user_id'=>$user_id,
        'account_type'=>'user_bank',
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
    $validated = $request->validate([
        // 'payment_geteway_id'=> 'required',
        'name'=> 'required',
        'description'=> 'required',
        'card_number'=> 'required',
        'ibank'=> 'required',
        'bank'=> 'required',
        'account_number'=> 'required',

    ]);
    $account->update([
        // 'payment_geteway_id'=>$request['payment_geteway_id'],
        'account_type'=>'user_bank',
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







