<?php

namespace App\Http\Controllers\V1\Expert\Cases;
use App\Http\Controllers\Controller;
use App\Http\Resources\Expert\CaseAssignmentCollection;
use App\Models\CaseAssignment;
use App\Models\CaseReport;
use App\Models\CaseReportComment;
use App\Models\CaseReportSurvery;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\CaseRequest;
use App\Models\FinancialAccount;
use App\Models\FinancialDocument;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

;


class CaseAssignController extends Controller
{
    public function __construct()
    {
        $this->middleware('role.expert_or_admin');
    }
    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
        
        $caseAssignments = CaseAssignment::where('user_id', $user_id)->paginate($perPage);
        return new CaseAssignmentCollection($caseAssignments);
    }

    public function assignCase(Request $request,$case_request_id){
      
        $user_id=$request->user()->id;
        $user=User::find($user_id);
        $caseReportComments = CaseReportComment::where('parent_id', null)->get();
        $case_request=CaseRequest::find($case_request_id);
        if ($case_request->status !== 'waiting' || $case_request->status !== 'referenced') {
            return response()->json(['success' => false, 'message' => 'Case request is not in waiting status.'], 200);
        }
        
        //check comment

        foreach ($caseReportComments as $caseReportComment) {
            $reply = CaseReportComment::where('parent_id', $caseReportComment->id)->first();
            if (!$reply) {
                return response()->json(['success' => false, 'message' => 'reply comment exist'], 200);
            }
            $reply = CaseReportComment::where('parent_id', $reply->id)->where('user_id', '!=', $user_id)->first();
            if ($reply) {
                return response()->json(['success' => false, 'message' => 'reply comment exist'], 200);
            }
        }
        if ($case_request->cloned_id) {
            $parentCaseRequest=CaseRequest::find($case_request->cloned_id);
            $caseAssignment=CaseAssignment::where('user_id', $user_id)->where('case_request_id',$parentCaseRequest->id)->first();
            if ($caseAssignment) {
                return response()->json(['success' => false, 'message' => 'you before case request original assign'], 200);
            }
        }
        $chechCaseAssignment = CaseAssignment::where('user_id', $user_id)->where('case_request_id',$case_request->id)->first();
        if ($chechCaseAssignment) {
            return response()->json(['success' => false, 'message' => 'you assign case before'], 200);

        }
        $user_id=$request->user()->id;

       $caseAssignment=CaseAssignment::create([
        'user_id'=>$user_id,
        'case_request_id'=>$case_request_id,
        'type'=>'waiting'
       ]);
    //    $case_request->update([
    //     'status'=>''
    //    ]);
  
       return response()->json(['success' => true, 'message' => 'true','data'=>[$caseAssignment]], 200);

    }



}







