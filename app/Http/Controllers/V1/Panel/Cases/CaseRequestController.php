<?php

namespace App\Http\Controllers\V1\Panel\Cases;
use App\Http\Controllers\Controller;
use App\Http\Resources\Panel\CaseRequestCollection;
use App\Http\Resources\Panel\CaseRequestResource;
use App\Models\CaseFile;
use App\Models\CaseRequestFields;
use App\Models\File;
use App\Models\User;
use App\Notifications\SendCaseRequestForExpert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\CaseRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CaseRequestController extends Controller
{
    public function __construct(Request $request)
    {
        // $user = Auth::guard('sanctum')->user();
        // if ($user->is_active === null) {
        //     abort(JsonResponse::HTTP_UNAUTHORIZED, 'not active user');
        // }
    }

    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
        
        $user = User::findOrFail($user_id);
        $caseRequests = $user->caseRequests();
    
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
    public function numberCaseUser(Request $request){
        $user_id=$request->user()->id;
        $user=User::where('id',$user_id)->first();
        $numCaseRequests = $user->caseRequests()->count();
        return response()->json(['success' => true, 'message' => 'true','data'=>$numCaseRequests], 200);

        //get CaseRequest user auth
    }
    public function numberCaseWatingUser(Request $request){
        $user_id=$request->user()->id;
        $user=User::where('id',$user_id)->first();
        $numCaseRequests = $user->caseRequests()->where('status','waiting')->count();
        return response()->json(['success' => true, 'message' => 'true','data'=>$numCaseRequests], 200);

        //get CaseRequest user auth
    }


    public function createCaseRequest(Request $request){
        $user_id=$request->user()->id;
        try {
            $validated = $request->validate([
                'case_category_animal_id'=> 'required|integer|exist:case_category_animal,id',
                'case_category_expert_id'=> 'required|integer|exist:case_categories_expert,id',

            'title'=>'required',
            'document_no'=>'required',
            'owner_name'=>'required',
            'animal_name'=>'required',
            'priority'=> 'required|in:speed,accurancy',
            'files' => 'required|array|min:2|max:20',
                        'files.*' => 'required|file', // Ensure each file in the array is present and a file
            ]);
    
            // Your logic for the API method goes here
        } catch (ValidationException $e) {
            // Validation failed, return a JSON response with validation errors
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    //    dd($request->file('files'));
    //     if(! $request->file('files')) {
    //         return response()->json(['errors' =>'the files field is required'], 422);

    //     }
    //     $fileCount = count($request->file('files'));
    //     if ($fileCount < 2 || $fileCount > 20) {
    //         return response()->json(['error' => 'You must upload between 2 and 20 files.'], 422);
    //     }
        // if ($validated->errors()) {
        //     return response()->json(['errors' => $validated->errors()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        // }
        // dd($validated);
       $caseRequest=CaseRequest::create([
        'user_id'=>$user_id,
        'case_category_animal_id'=>$request['case_category_animal_id'],
        'case_category_expert_id'=>$request['case_category_expert_id'],
        'title'=>$request['title'],
        'document_no'=>$request['document_no'],
        'owner_name'=>$request['owner_name'],
        'animal_name'=>$request['animal_name'],
        'priority'=>$request['priority'],
        'status'=>'draft',
       ]);
       $caseCategoryIds=$request['categories'];
       foreach ($caseCategoryIds as $categoryId) {
        DB::table('case_request_category')->insert([
            'case_request_id' => $caseRequest->id,
            'case_category_id' => $categoryId,

        ]);
            foreach ($request['case_request_fields'] as $caseRequestField) {
                $caseRequestField=json_decode($caseRequestField, true);

            $caseSurvey = new CaseRequestFields();
            $caseSurvey->case_request_id = $caseRequest->id;
            $caseSurvey->case_category_field_id = $caseRequestField['case_category_field_id'];
            $caseSurvey->value = $caseRequestField['value'];
            $caseSurvey->save();
           }
      
           if($request->file('files')) {
        
            foreach($request->file('files') as $file){
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
        $experts = User::role('expert')->whereNotNull('is_active')->get();
        
        foreach ($experts as $expert) {
            $expert->notify(new SendCaseRequestForExpert($expert));        
        }
    }
       return response()->json(['success' => true, 'message' => 'true','data'=>[$caseRequest]], 200);

    }
    public function show(Request $request, CaseRequest $caseRequest){
        return new CaseRequestResource($caseRequest);
    }
    

    public function cloneCaseRequest(Request $request, $caseRequestId){
        // Retrieve the original case request
        $originalCaseRequest = CaseRequest::findOrFail($caseRequestId);
    
        // Clone the case request
        $clonedCaseRequest = $originalCaseRequest->replicate();
        $clonedCaseRequest->status = 'draft'; // Set status to draft for the cloned request
        $clonedCaseRequest->save();
    
        // Clone associated case request categories
        $originalCaseRequestCategories = $originalCaseRequest->categories()->get();
        foreach($originalCaseRequestCategories as $category){
            $clonedCaseRequest->categories()->attach($category->id);
        }
    
        // Clone associated case request fields
        $originalCaseRequestFields = $originalCaseRequest->caseRequestFields()->get();
        foreach($originalCaseRequestFields as $field){
            $clonedField = $field->replicate();
            $clonedField->case_request_id = $clonedCaseRequest->id;
            $clonedField->save();
        }
    
        // Clone associated files
        $caseFiles = $originalCaseRequest->caseFiles;
        // dd($caseFiles);
        foreach($caseFiles as $caseFile){
            $document = new File;

            
            $document->src=$caseFile->file->src;
            $document->name=$caseFile->file->name;
            $document->type='file';
            $document->parent_id=7;
            $document->save();
            $caseFile = new CaseFile;
            $caseFile->file_id =$document->id;
            $caseFile->case_request_id=$clonedCaseRequest->id;
            $caseFile->save();
        }
    
        return response()->json(['success' => true, 'message' => 'Case request cloned successfully', 'data' => $clonedCaseRequest], 200);
    }
    


}







