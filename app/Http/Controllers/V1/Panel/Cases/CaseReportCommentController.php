<?php

namespace App\Http\Controllers\V1\Panel\Cases;
use App\Http\Controllers\Controller;
use App\Http\Resources\Panel\CaseReportCommentCollection;
use App\Http\Resources\Panel\CaseReportCommentResource;
use App\Http\Resources\Panel\CaseRequestCollection;
use App\Models\CaseReportComment;
use App\Models\CaseRequestFields;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\CaseRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CaseReportCommentController extends Controller
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
        $caseReportComments = $user->caseReportComments()->paginate($perPage);
    
        return new CaseReportCommentCollection($caseReportComments);
    }
    public function show(CaseReportComment $casereportcomment){
        return new CaseReportCommentResource($casereportcomment);
    }

    public function store(Request $request){
        $user_id=$request->user()->id;
        try {
            $validated = $request->validate([

            
                'parent_id'=>'nullable',
                'message'=>'required',
                'case_report_id' => 'required|integer|exists:case_reports,id',

               
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $caseReportComment=CaseReportComment::create([
            'parent_id'=> $request['parent_id'],
            'user_id'=> $user_id,
            'case_report_id'=> $request['case_report_id'],
            'message'=> $request['message'],

           ]);
           return response()->json(['success' => true, 'message' => 'true','data'=>[$caseReportComment]], 200);
    }

    public function update(Request $request,CaseReportComment $casereportcomment){
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

             
                'parent_id'=>'nullable',
                'message'=>'required',
                'case_report_id' => 'required|integer|exists:case_reports,id',

               
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $user_id=$request->user()->id;

        $casereportcomment->update([

            'parent_id'=> $request['parent_id'],
            'user_id'=> $user_id,
            'case_report_id'=> $request['case_report_id'],
            'message'=> $request['message'],
            'status'=> $request['status'],

        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$casereportcomment]], 200);
    }

    public function destroy(CaseReportComment $casereportcomment){
        $casereportcomment->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }




}







