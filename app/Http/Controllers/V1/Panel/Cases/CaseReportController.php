<?php

namespace App\Http\Controllers\V1\Panel\Cases;
use App\Http\Controllers\Controller;
use App\Http\Resources\Panel\CaseReportResource;
use App\Http\Resources\Panel\CaseRequestCollection;
use App\Models\CaseReport;
use App\Models\CaseRequestFields;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\CaseRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

;
use Illuminate\Support\Facades\DB;


class CaseReportController extends Controller
{
    public function __construct(Request $request)
    {
        // $user = Auth::guard('sanctum')->user();
        // if ($user->is_active === null) {
        //     abort(JsonResponse::HTTP_UNAUTHORIZED, 'not active user');
        // }
    }
    public function showReport(Request $request,CaseReport $caseReport){
        $user_id=$request->user()->id;
        $user=User::find($user_id);
       return new CaseReportResource($caseReport);

    }
    // public function showReportByCaseRequest(Request $request,CaseRequest $caseRequest){
    //     $user_id=$request->user()->id;
    //     $user=User::find($user_id);
    //    return new CaseReportResource($caseRequest->assignments);

    // }
    public function myScore(Request $request){
        $user_id=$request->user()->id;
        $user=User::find($user_id);
       $score=$user->caseAssignments->flatMap(function ($assignment) {
             $assignment->caseReports->pluck('case_score');
        })->avg();
        return response()->json(['success' => true, 'message' => 'true','data'=>[$score]], 200);

    }
    public function giveScore(Request $request,CaseReport $caseReport){
         $caseReport->update([
            'report_score'=>$request['report_score']
         ]);
        return response()->json(['success' => true, 'message' => 'true','data'=>[$caseReport]], 200);

        // $user=User::where('id',$user_id)->first();

        // return new CaseRequestCollection($user->caseRequests);
        //get CaseRequest user auth
    }





}







