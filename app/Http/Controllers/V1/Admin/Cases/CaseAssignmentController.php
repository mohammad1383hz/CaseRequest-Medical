<?php

namespace App\Http\Controllers\V1\Admin\Cases;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CaseAssignmentCollection;
use App\Http\Resources\Admin\CaseAssignmentResource;
use App\Http\Resources\Admin\CaseReportSurveryFieldCollection;
use App\Http\Resources\Admin\CaseReportSurveryFieldResource;

use App\Models\CaseAssignment;
use App\Models\CaseReportSurveryField;
use App\Models\CaseRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CaseAssignmentController extends Controller
{
    public function __construct()
    {
          $this->middleware(['permission:caseAssignment.index'])->only('index');
        $this->middleware(['permission:caseAssignment.store'])->only('store');
        $this->middleware(['permission:caseAssignment.show'])->only('show');
        $this->middleware(['permission:caseAssignment.update'])->only('update');
        $this->middleware(['permission:caseAssignment.destroy'])->only('destroy');
    }

    public function index(Request $request) {
        $perPage = $request->query('per_page', 10);
        $caseAssignments = CaseAssignment::paginate($perPage);
        return new CaseAssignmentCollection($caseAssignments);
    }
    public function show(CaseAssignment $assignment){
        return new CaseAssignmentResource($assignment);
    }

    public function store(Request $request){
        try {
          $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'case_request_id' => 'required|integer|exists:case_requests,id',
            'type'=>'required',
        ]);
        } catch (ValidationException $e) {
            // Validation failed, return a JSON response with validation errors
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
      
        $CaseAssignment=CaseAssignment::create([
            'user_id'=>$request['user_id'],
            'case_request_id'=>$request['case_request_id'],
            'type'=>$request['type'],
           ]);
           return response()->json(['success' => true, 'message' => 'true','data'=>[$CaseAssignment]], 200);
    }

    public function update(Request $request,CaseAssignment $assignment){
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'case_request_id' => 'required|integer|exists:case_requests,id',
              'type'=>'required',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        
        $assignment->update([
            'user_id'=>$request['user_id'],
            'case_request_id'=>$request['case_request_id'],
            'type'=>$request['type'],
        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$assignment]], 200);
    }

    public function destroy(CaseAssignment $assignment){
        $assignment->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }

}
