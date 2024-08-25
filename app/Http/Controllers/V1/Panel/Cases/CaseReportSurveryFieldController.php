<?php

namespace App\Http\Controllers\V1\Panel\Cases;
use App\Http\Controllers\Controller;
use App\Http\Resources\Panel\CaseReportSurveryFieldCollection;
use App\Models\CaseAssignment;
use App\Models\CaseReport;
use App\Models\CaseReportSurvery;
use App\Models\CaseReportSurveryField;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\CaseRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

;


class CaseReportSurveryFieldController extends Controller
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
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
        $CaseReportSurveryFields = CaseReportSurveryField::paginate($perPage);
        return new CaseReportSurveryFieldCollection($CaseReportSurveryFields);
    }
    public function makeSurveryField(Request $request,$caseReport){
        $user_id=$request->user()->id;

        foreach ($request['case_report_surveries'] as $caseSurvery) {
            $caseSurvey = new CaseReportSurvery();
            $caseSurvey->user_id = $user_id;
            $caseSurvey->case_report_id = $caseReport;
            $caseSurvey->case_survery_field_id = $caseSurvery['case_survery_field_id'];
            $caseSurvey->value = $caseSurvery['value'];
            $caseSurvey->save();
           }
           return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }




}







