<?php

namespace App\Http\Controllers\V1\Admin\Cases;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CaseReportSurveryCollection;
use App\Http\Resources\Admin\CaseReportSurveryFieldCollection;
use App\Http\Resources\Admin\CaseReportSurveryFieldResource;
use App\Http\Resources\Admin\CaseReportSurveryResource;
use App\Models\CaseReportSurvery;
use App\Models\CaseReportSurveryField;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CaseReportSurveryController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:caseReportSurvey.index'])->only('index');
        $this->middleware(['permission:caseReportSurvey.store'])->only('store');
        $this->middleware(['permission:caseReportSurvey.show'])->only('show');
        $this->middleware(['permission:caseReportSurvey.update'])->only('update');
        $this->middleware(['permission:caseReportSurvey.destroy'])->only('destroy');
    }

    public function index(Request $request) {
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
        $reportsurveries = CaseReportSurvery::paginate($perPage);
        return new CaseReportSurveryCollection($reportsurveries);
    }
    public function show(CaseReportSurvery $reportsurvery){
        return new CaseReportSurveryResource($reportsurvery);
    }

    public function store(Request $request){
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'case_survery_field_id'=>'required',
                'case_report_id' => 'required|integer|exists:case_reports,id',
                'value'=>'required',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
           

         
        $reportsurvery=CaseReportSurvery::create([
            'user_id'=> $request['user_id'],
            'case_survery_field_id'=> $request['case_survery_field_id'],
            'case_report_id'=> $request['case_report_id'],
            'value'=> $request['value'],
         
        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$reportsurvery]], 200);
    }

    public function update(Request $request,CaseReportSurvery $reportsurvery){
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'case_survery_field_id'=>'required',
                'case_report_id' => 'required|integer|exists:case_reports,id',
                'value'=>'required',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $reportsurvery->update([
            'user_id'=> $request['user_id'],
            'case_survery_field_id'=> $request['case_survery_field_id'],
            'case_report_id'=> $request['case_report_id'],
            'value'=> $request['value'],
         
        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$reportsurvery]], 200);
    }

    public function destroy(CaseReportSurvery $reportsurvery){
        $reportsurvery->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }

}
