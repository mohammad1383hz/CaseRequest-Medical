<?php

namespace App\Http\Controllers\V1\Panel\Cases;
use App\Http\Controllers\Controller;
use App\Http\Resources\Panel\CaseRequestCollection;
use App\Http\Resources\Panel\CaseResearchCollection;
use App\Http\Resources\Panel\CaseResearchResource;
use App\Models\CaseFile;
use App\Models\CaseRequestFields;
use App\Models\CaseResearch;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\CaseRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CaseResearchController extends Controller
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
    
        $caseResearch = CaseResearch::whereHas('caseRequest', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->paginate($perPage);
    
        return new CaseResearchCollection($caseResearch);
    }
    public function show(Request $request,CaseResearch $caseResearch){


        return new CaseResearchResource($caseResearch);
        //get CaseRequest user auth
    }


    public function store(Request $request){
        $user_id=$request->user()->id;
        try {
            $validated = $request->validate([

                'case_request_id' => 'required|integer|exists:case_requests,id',

                'title'=> 'required',
                'description'=> 'required',

                'files' => 'nullable|array|min:1|max:20',
                'files.*' => 'nullable|file', // En
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
       $caseResearch=CaseResearch::create([
        'case_request_id'=>$request['case_request_id'],
        'title'=>$request['title'],
        'description'=>$request['description'],
       ]);
       if($request->file('file')) {
        foreach($request->file('file') as $file){
            $document = new File;

            $file_name = time().'_'.'Research'.$file->getClientOriginalName();
            $file_path = $file->storeAs('case_research_file', $file_name, 'public');
            $path='storage/'.$file_path;
            $document->src=$path;
            $document->type='file';
            $document->name=$file_name;
         
            $document->parent_id=6;
            $document->save();
            $caseFile = new CaseFile;
            $caseFile->file_id =$document->id;
            $caseFile->case_research_id=$caseResearch->id;
            $caseFile->save();

            }
    }
       return response()->json(['success' => true, 'message' => 'true','data'=>[$caseResearch]], 200);



    }
    public function update(Request $request,CaseResearch $caseResearch){
        $user_id=$request->user()->id;
        try {
            $validated = $request->validate([

                'case_request_id' => 'required|integer|exists:case_requests,id',

                'title'=> 'required',
                'description'=> 'required',

                'files' => 'nullable|array|min:1|max:20',
                'files.*' => 'nullable|file', // En
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
       $caseResearch->update([
        'case_request_id'=>$request['case_request_id'],
        'title'=>$request['title'],
        'description'=>$request['description'],
       ]);
       if($request->file('file')) {
        foreach($request->file('file') as $file){
            $document = new File;

            $file_name = time().'_'.'Research'.$file->getClientOriginalName();
            $file_path = $file->storeAs('case_research_file', $file_name, 'public');
            $path='storage/'.$file_path;
            $document->src=$path;
            $document->type='file';
            $document->name=$file_name;
         
            $document->parent_id=6;
            $document->save();
            $caseFile = new CaseFile;
            $caseFile->file_id =$document->id;
            $caseFile->case_research_id=$caseResearch->id;
            $caseFile->save();

            }
    }
       return response()->json(['success' => true, 'message' => 'true','data'=>[$caseResearch]], 200);



    }
    public function destroy(CaseResearch $caseResearch){
        $caseResearch->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }

    }












