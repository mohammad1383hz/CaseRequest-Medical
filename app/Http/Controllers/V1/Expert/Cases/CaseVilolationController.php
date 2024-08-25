<?php

namespace App\Http\Controllers\V1\Expert\Cases;
use App\Http\Controllers\Controller;
use App\Http\Resources\Expert\CaseViolationCollection;
use App\Http\Resources\Expert\CaseViolationResource;
use App\Models\CaseAssignment;
use App\Models\CaseReport;
use App\Models\CaseReportSurvery;
use App\Models\CaseViolation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\CaseRequest;
use Illuminate\Validation\ValidationException;

;


class CaseVilolationController extends Controller
{

    public function __construct()
        {
            $this->middleware('role.expert_or_admin');
        }
    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
    
        $caseViolations = CaseViolation::where('user_id', $user_id)->paginate($perPage);
        
        return new CaseViolationCollection($caseViolations);
    }
    public function show(CaseViolation $caseViolation){
        return new CaseViolationResource($caseViolation);
    }

    public function store(Request $request){
        try {
            $validated = $request->validate([
                'case_request_id'=> 'required|integer|exist:case_requests,id',
                'title'=> 'required',
                'description'=> 'required',
               
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $user_id=$request->user()->id;

        $CaseViolation=CaseViolation::create([
            'user_id'=>$user_id,
            'case_request_id'=>$request['case_request_id'],
            'title'=>$request['title'],
            'description'=>$request['description'],
           ]);
           return response()->json(['success' => true, 'message' => 'true','data'=>[$CaseViolation]], 200);
    }

    public function update(Request $request,CaseViolation $caseViolation){
        try {
            $validated = $request->validate([
                'case_request_id'=> 'required|integer|exist:case_requests,id',
                'title'=> 'required',
                'description'=> 'required',
               
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $caseViolation->update([
            'case_request_id'=>$request['case_request_id'],
            'title'=>$request['title'],
            'description'=>$request['description'],

        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$caseViolation]], 200);
    }

    public function destroy(CaseViolation $caseViolation){
        $caseViolation->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }


}







