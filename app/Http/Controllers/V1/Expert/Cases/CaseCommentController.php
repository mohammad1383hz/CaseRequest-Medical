<?php

namespace App\Http\Controllers\V1\Expert\Cases;
use App\Http\Controllers\Controller;
use App\Http\Resources\Expert\CaseReportCollection;
use App\Models\CaseAssignment;
use App\Models\CaseReport;
use App\Models\CaseReportComment;
use App\Models\CaseReportSurvery;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\CaseRequest;
use Illuminate\Validation\ValidationException;

;


class CaseCommentController extends Controller
{

    public function __construct()
{
    $this->middleware('role.expert_or_admin');
}
    public function index(Request $request){
        //get CaseRequest user auth
        $per_page=$request->query("per_page");
        $CaseReportComment=CaseReportComment::where("user_id", $request->user()->id)->orderBy("created_at","desc")->paginate($per_page);


        return new CaseReportCollection($CaseReportComment);    
    }



    public function replyComment(Request $request,CaseReportComment $caseReportComment){
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
        $caseReportComment = CaseReportComment::create([
            'parent_id'=>$request['parent_id'],
            'user_id'=>$user_id,
            'case_report_id'=>$request['case_report_id'],
            'message'=>$request['message'],
            // 'status'=>$request['status'],
        ]);
        return response()->json(['success' => true, 'message' => 'true','data'=>[$caseReportComment]], 200);

    }
    public function update(Request $request,CaseReportComment $caseReportComment){
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
        $caseReportComment ->update([
            'parent_id'=>$request['parent_id'],
            'case_report_id'=>$request['case_report_id'],
            'message'=>$request['message'],
            // 'status'=>$request['status'],
        ]);
        return response()->json(['success' => true, 'message' => 'true','data'=>[$caseReportComment]], 200);

    }

}







