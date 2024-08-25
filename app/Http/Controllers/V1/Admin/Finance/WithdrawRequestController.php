<?php

namespace App\Http\Controllers\V1\Admin\Finance;
use App\Http\Controllers\Controller;

use App\Http\Resources\Admin\CouponCollection;
use App\Http\Resources\Admin\CouponResource;

use App\Http\Resources\Admin\FinancialAccountCollection;
use App\Http\Resources\Admin\FinancialAccountResource;
use App\Http\Resources\Admin\WithdrawRequestCollection;
use App\Http\Resources\Admin\WithdrawRequestResource;
use App\Models\CaseCategory;
use App\Models\File;
use App\Models\FinancialAccount;
use App\Models\FinancialDocument;
use App\Models\Invoice;
use App\Models\User;
use App\Models\WithdrawRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class WithdrawRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:withdrawRequest.index'])->only('index');
        $this->middleware(['permission:withdrawRequest.show'])->only('show');
        $this->middleware(['permission:withdrawRequest.update'])->only('update');
        $this->middleware(['permission:withdrawRequest.destroy'])->only('destroy');

    }
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
        $withdrawRequests = WithdrawRequest::paginate($perPage);
        return new WithdrawRequestCollection($withdrawRequests);
    }
    
public function show(WithdrawRequest $withdrawRequest){
    return new WithdrawRequestResource($withdrawRequest);

}

    public function store(Request $request){
        // $user_id=$request->user()->id;
        // $dataInventory=$this->inventory($request);
        // $responseData = $dataInventory->getData();
        // $inventoryWallet = $responseData->data;
        // if($request['price'] > $inventoryWallet){
        //     return response()->json(['success' => true, 'message' => 'not inventory','data'=>null], 200);
        // }
        // $date = Carbon::now();
        // $withdrawRequest=WithdrawRequest::create([
        //     'user_id'=>$user_id,
        //     'financial_account_id'=>$request['financial_account_id'],
        //     'status'=>'requested',
        //     'price'=>$request['price'],
        //     'description'=>$request['description'],
        //     'date'=>$date,
        // ]);
        // return response()->json(['success' => true, 'message' => 'true','data'=>[$withdrawRequest]], 200);

        // //get CaseRequest user auth
    }
   
    public function update(Request $request,WithdrawRequest $withdrawRequest){
        try {
            $validated = $request->validate([
                'price'=>'required|integer',
                'status'=>'required',
                'date'=>'required',
                'description'=>'nullable',
                'financial_account_id'=>'nullable|integer|exists:financial_accounts,id',





               
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $date = Carbon::now();
        if($request->file('file')) {
         $file=$request->file('file');
                $document = new File;

                $file_name = time().'_'.'Request'.$file->getClientOriginalName();
                $file_path = $file->storeAs('withdrawRequest_file', $file_name, 'public');
                $path='storage/'.$file_path;
                $document->src=$path;
                $document->name=$file_name;
                $document->type='file';
                $document->parent_id=9;
                $document->save();
            

        }
        $withdrawRequest->update([
           
            'financial_account_id'=>$request['financial_account_id'],
            'status'=>$request['status'],
            'price'=>$request['price'],
            'description'=>$request['description'],
            'file_id'=>$path,
            'date'=>$date,
        ]);
        return response()->json(['success' => true, 'message' => 'true','data'=>[$withdrawRequest]], 200);

    }
    public function destroy(Request $request,WithdrawRequest $withdrawRequest){
                $withdrawRequest->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }
    }







