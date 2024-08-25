<?php

namespace App\Http\Controllers\V1\Expert\Cases;
use App\Http\Controllers\Controller;
use App\Http\Resources\Expert\CaseRequestCollection;
use App\Models\CaseAssignment;
use App\Models\CaseReport;
use App\Models\CaseReportSurvery;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\CaseRequest;
use App\Models\CaseViolation;
use App\Notifications\EediRequiredCaseRequestNotification;

;


class CaseRequestController extends Controller
{
      public function __construct()
    {
        $this->middleware('role.expert_or_admin');
    }
  public function index(Request $request)
  {
      $user_id = $request->user()->id;
      $user = User::where('id', $user_id)->first();
  
      $group_id = $user->group_id;
    // dd($group_id);
      $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
  
      $caseRequests = CaseRequest::where('status','waiting')->whereHas('categories', function ($query) use ($group_id) {
          $query->where('case_group_id', $group_id);
      })->paginate($perPage);

      if($user->hasRole('expert-supervisor')) {
        $caseRequests = CaseRequest::where(function ($query) use ($group_id) {
          $query->where('status', 'waiting')
                ->orWhere('status', 'referenced');
      })
      ->whereHas('categories', function ($query) use ($group_id) {
          $query->where('case_group_id', $group_id);
      })
      ->paginate($perPage);
      
      }
      
      return new CaseRequestCollection($caseRequests);
  }

    public function statusUpdateCaseRequest(Request $request,CaseRequest $caseRequest){
      $user_id = $request->user()->id;
        $caseRequest->update([
          'status'=>'edit_required'
        ]);
        $CaseViolation=CaseViolation::create([
          'user_id'=>$user_id,
          'case_request_id'=>$caseRequest,
          'title'=>$request['title'],
          'description'=>$request['description'],
         ]);
        // notif
        $usersNotif=User::where('id',$caseRequest->user_id)->first();
        foreach ($usersNotif as $user) {
          $user->notify(new EediRequiredCaseRequestNotification($user));        
      }
         return response()->json(['success' => true, 'message' => 'true','data'=>[$caseRequest]], 200);

      }
      public function complateCaseRequest(Request $request,CaseRequest $caseRequest){
        $caseRequest->update([
          'status'=>'submitted'
        ]);
        $refrence_index=$caseRequest->caseCategoryExpert->refrence_index;
        if($refrence_index > 1 && $caseRequest->times_refernced < $refrence_index){
            $caseRequest->update([
                'status'=>'referenced'
              ]);
              $caseRequest->increment('times_referenced');

        } 
        // notif
         return response()->json(['success' => true, 'message' => 'true','data'=>[$caseRequest]], 200);

      }


}







