<?php

namespace App\Http\Controllers\V1\Admin\Cases;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CaseRequestCollection;
use App\Http\Resources\Admin\CaseRequestResource;
use App\Models\CaseReportSurveryField;
use App\Models\CaseRequest;
use App\Models\CaseRequestFields;
use App\Models\File;
use App\Models\FinancialAccount;
use App\Models\FinancialDocument;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CaseFile;
use Illuminate\Validation\ValidationException;

class CaseRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:caseRequest.index'])->only('index');
        $this->middleware(['permission:caseRequest.store'])->only('store');
        $this->middleware(['permission:caseRequest.show'])->only('show');
        $this->middleware(['permission:caseRequest.update'])->only('update');
        $this->middleware(['permission:caseRequest.destroy'])->only('destroy');
    }
    public function index(Request $request) {
        $status = $request->query('status');
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
        
        $caseRequests = CaseRequest::query();
        
        if ($status) {
            $caseRequests->where('status', $status);
        }

        $document_no = $request->query('document_no');
        $owner_name = $request->query('owner_name');
        $animal_name = $request->query('animal_name');
        $category_id = $request->query('category_id');
    
        if ($document_no) {
            $caseRequests->where('document_no', $document_no);
        }
        if ($owner_name) {
            $caseRequests->where('owner_name', $owner_name);
        }
        if ($animal_name) {
            $caseRequests->where('animal_name', $animal_name);
        }
        if ($category_id) {
            $caseRequests->whereHas('categories', function ($query) use ($category_id) {
                $query->where('id', $category_id);
            });
        }
    
        $caseRequests = $caseRequests->paginate($perPage);
        
        return new CaseRequestCollection($caseRequests);
    }
    public function numberCase(Request $request){
        $status=$request->query('status');
        if($status){
            $caseRequestcount=CaseRequest::where('status',$status)->count();

        }else{
            $caseRequestcount=CaseRequest::count();

        }

        return response()->json(['success' => true, 'message' => 'true','data'=>$caseRequestcount], 200);

    }
    public function show(CaseRequest $request){
        return new CaseRequestResource($request);
    }

    public function store(Request $request){
        // $validated = $request->validate([
        //     'id' =>'required',
        //     'name'=>'required',
        //     'title'=>'required',
        //     'placeholder'=>'required',
        //     'type'=>'required',
        //     'number'=> 'required',
        // ]);
        try {
            $validated = $request->validate([
                'user_id'=> 'required|integer|exist:users,id',
                'case_category_animal_id'=> 'required|integer|exist:case_category_animal,id',
                'case_category_expert_id'=> 'required|integer|exist:case_categories_expert,id',

              
                'title'=>'required',
                'document_no'=> 'required',
                'owner_name'=> 'required',
                'animal_name'=> 'required',
                'priority'=> 'required|in:speed,accurancy',
                'status'=> 'required|in:submitted,waiting,done,cancelled,refernced,edit_required,draft,block',

          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $caseRequest=CaseRequest::create([
            'user_id'=>$request['user_id'],
            'case_category_animal_id'=>$request['case_category_animal_id'],
            'case_category_expert_id'=>$request['case_category_expert_id'],
            'title'=>$request['title'],
            'document_no'=>$request['document_no'],
            'owner_name'=>$request['owner_name'],
            'animal_name'=>$request['animal_name'],
            'priority'=>$request['priority'],
            'status'=>$request['status'],
           ]);
           $caseCategoryIds=$request['categories'];
           foreach ($caseCategoryIds as $categoryId) {
            DB::table('case_request_category')->insert([
                'case_request_id' => $caseRequest->id,
                'case_category_id' => $categoryId,

            ]);}
            foreach ($request['case_request_fields'] as $caseRequestField) {
                $caseSurvey = new CaseRequestFields();
                $caseSurvey->case_request_id = $caseRequest->id;
                $caseSurvey->case_category_field_id = $caseRequestField['case_category_field_id'];
                $caseSurvey->value = $caseRequestField['value'];
                $caseSurvey->save();
               }
               if($request->file('file')) {
                foreach($request->file('file') as $file){
                    $document = new File;
    
                    $file_name = time().'_'.'Request'.$file->getClientOriginalName();
                    $file_path = $file->storeAs('case_request_file', $file_name, 'public');
                    $path='storage/'.$file_path;
                    $document->src=$path;
                    $document->name=$file_name;
                    $document->type='file';
                    $document->parent_id=8;
                    $document->save();
                    $caseFile = new CaseFile;
                    $caseFile->file_id =$document->id;
                    $caseFile->case_request_id=$caseRequest->id;
                    $caseFile->save();
    
                    }
            }
           return response()->json(['success' => true, 'message' => 'true','data'=>[$caseRequest]], 200);
    }

    public function update(Request $request,$caseRequest){
        $caseRequest=CaseRequest::find($caseRequest);

        try {
            $validated = $request->validate([
                'user_id'=> 'required|integer|exist:users,id',
                'case_category_animal_id'=> 'required|integer|exist:case_category_animal,id',
                'case_category_expert_id'=> 'required|integer|exist:case_categories_expert,id',

              
                'title'=>'required',
                'document_no'=> 'required',
                'owner_name'=> 'required',
                'animal_name'=> 'required',
                'priority'=> 'required|in:speed,accurancy',
                'status'=> 'required|in:submitted,waiting,done,cancelled,refernced,edit_required,draft,block',

          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $caseRequest->update([
            'user_id'=>$request['user_id'],
            'case_category_animal_id'=>$request['case_category_animal_id'],
            'case_category_expert_id'=>$request['case_category_expert_id'],
            'title'=>$request['title'],
            'document_no'=>$request['document_no'],
            'owner_name'=>$request['owner_name'],
            'animal_name'=>$request['animal_name'],
            'priority'=>$request['priority'],
            'status'=>$request['status'],
        ]);
        $caseCategoryIds=$request['categories'];
        foreach ($caseCategoryIds as $categoryId) {
         DB::table('case_request_category')->insert([
             'case_request_id' => $caseRequest->id,
             'case_category_id' => $categoryId,

         ]);}
         foreach ($request['case_request_fields'] as $caseRequestField) {
             $caseSurvey = new CaseRequestFields();
             $caseSurvey->case_request_id = $caseRequest->id;
             $caseSurvey->case_category_field_id = $caseRequestField['case_category_field_id'];
             $caseSurvey->value = $caseRequestField['value'];
             $caseSurvey->save();
            }
            if($request->file('file')) {
             foreach($request->file('file') as $file){
                 $document = new File;
 
                 $file_name = time().'_'.'Request'.$file->getClientOriginalName();
                 $file_path = $file->storeAs('case_request_file', $file_name, 'public');
                 $path='storage/'.$file_path;
                 $document->src=$path;
                 $document->save();
                 $caseFile = new CaseFile;
                 $caseFile->file_id =$document->id;
                 $caseFile->case_request_id=$caseRequest->id;
                 $caseFile->save();
 
                 }
         }
        return response()->json(['success' => true, 'message' => 'true','data'=>[$caseRequest]], 200);
    }

    public function destroy($caseRequest){
        $caseRequest=CaseRequest::find($caseRequest);

        $caseRequest->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }
    public function cancelCaseRequest(Request $request,$caseRequest){
        $user_id=$request->user()->id;
        $caseRequest=CaseRequest::find($caseRequest);
        $user=User::find($user_id);
        $caseRequest->update([
            'status'=>'canceled'
        ]);
        $financialAccountUser=FinancialAccount::where('user_id',$user_id)->where('account_type','user')->first();
        $financialAccountApp= FinancialAccount::where('account_type','app')->first();
        $financialDocument= FinancialDocument::create([
         'creditor_id'=>$financialAccountApp->id,
         'debtor_id'=>$financialAccountUser->id,
         // 'description'
         // 'tracking_code'
         'date'=>Carbon::now(),
         'price'=>$caseRequest->getTotalPrice(),
         'currency_id'=>$user->currency_id,
     ]);
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }
    public function getNumberCases(){
        $caseCount = CaseRequest::count();

        return response()->json(['success' => true, 'message' => 'true','data'=>[$caseCount]], 200);

    }
    public function getNumberCasesOnMounth(){
        $now = Carbon::now();
        $caseCount = DB::table('case_requests')
                ->whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->count();
        return response()->json(['success' => true, 'message' => 'true','data'=>$caseCount], 200);

    }


}
