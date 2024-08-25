<?php

namespace App\Http\Controllers\V1\Admin\Finance;
use App\Http\Controllers\Controller;



use App\Http\Resources\Admin\FinancialDocumentCollection;
use App\Http\Resources\Admin\FinancialDocumentResource;
use App\Models\FinancialDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class FinancialDocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:financialDocument.index'])->only('index');
        $this->middleware(['permission:financialDocument.store'])->only('store');
        $this->middleware(['permission:financialDocument.show'])->only('show');
        $this->middleware(['permission:financialDocument.update'])->only('update');
        $this->middleware(['permission:financialDocument.destroy'])->only('destroy');

    }
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
        $sortBy = $request->query('sort_by', 'id'); // Default to sorting by 'id' if not specified
        $financialDocuments = FinancialDocument::orderBy($sortBy, 'desc')->paginate($perPage);
        return new FinancialDocumentCollection($financialDocuments);
    }
    
    public function show(FinancialDocument $document){
        return new FinancialDocumentResource($document);

    }

    public function store(Request $request){
        try {
            $validated = $request->validate([
                'creditor_id'=> 'required',
            'debtor_id'=> 'required',
            'description'=> 'nullable',
            'tracking_code'=> 'nullable',
            'date'=>'required',
            'price'=>'required',
            'is_canceled'=>'required',
            'invoice_id'=>'required',
            'file_id'=>'required',
            'withdraw_request_id'=>'required',
               
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $financialDocument=FinancialDocument::create([
            'creditor_id' =>$request['creditor_id'],
            'debtor_id' =>$request['debtor_id'],
            'description' =>$request['description'],
            'tracking_code' =>$request['tracking_code'],
            'date' =>$request['date'],
            'price' =>$request['price'],
            'is_canceled' =>$request['is_canceled'],
            'invoice_id' =>$request['invoice_id'],
            'file_id' =>$request['file_id'],
            'withdraw_request_id' =>$request['withdraw_request_id'],
        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$financialDocument]], 200);
    }

    public function update(Request $request,FinancialDocument $document){
        try {
            $validated = $request->validate([
                'creditor_id' => 'required|integer|exists:financial_accounts,id',
                'debtor_id' => 'required|integer|exists:financial_accounts,id',


            'description'=> 'nullable',
            'tracking_code'=> 'nullable',
            'date'=>'required',
            'price'=>'required|integer',
            'is_canceled'=>'required',
            'invoice_id'=>'nullable|integer|exists:invoices,id',
            'file_id'=>'nullable|integer|exists:files,id',
            'withdraw_request_id'=>'nullable|integer|exists:withdraw_requests,id',


               
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $document->update([
            'creditor_id' =>$request['creditor_id'],
            'debtor_id' =>$request['debtor_id'],
            'description' =>$request['description'],
            'tracking_code' =>$request['tracking_code'],
            'date' =>$request['date'],
            'price' =>$request['price'],
            'is_canceled' =>$request['is_canceled'],
            'invoice_id' =>$request['invoice_id'],
            'file_id' =>$request['file_id'],
            'withdraw_request_id' =>$request['withdraw_request_id'],
        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$document]], 200);
    }
    public function destroy(FinancialDocument $document){
        $document->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }
    }







