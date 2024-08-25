<?php

namespace App\Http\Controllers\V1\Admin\Cases;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CaseReportSurveryFieldCollection;
use App\Http\Resources\Admin\CaseReportSurveryFieldResource;
use App\Models\CaseReportSurveryField;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CaseReportSurveryFieldsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:caseReportSurveyField.index'])->only('index');
        $this->middleware(['permission:caseReportSurveyField.store'])->only('store');
        $this->middleware(['permission:caseReportSurveyField.show'])->only('show');
        $this->middleware(['permission:caseReportSurveyField.update'])->only('update');
        $this->middleware(['permission:caseReportSurveyField.destroy'])->only('destroy');
    }

    public function index(Request $request) {
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
        $caseReportSurveryFields = CaseReportSurveryField::paginate($perPage);
        return new CaseReportSurveryFieldCollection($caseReportSurveryFields);
    }
    public function show(CaseReportSurveryField $reportsurveryfiled){
        return new CaseReportSurveryFieldResource($reportsurveryfiled);
    }

    public function store(Request $request){
        try {
            $validated = $request->validate([
                'name'=>'required',
                'title'=>'required',
                'placeholder'=>'required',
                'type'=>'required',
                'number'=> 'required',
                'options'=> 'nullable',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
    
        $options = json_encode($request['options']);

        $caseReportSurveryField=CaseReportSurveryField::create([
            'name'=> $request['name'],
            'title'=> $request['title'],
            'placeholder'=> $request['placeholder'],
            'type'=> $request['type'],
            'number'=> $request['number'],
            'options'=>$options,



        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$caseReportSurveryField]], 200);
    }

    public function update(Request $request,CaseReportSurveryField $reportsurveryfiled){
        try {
            $validated = $request->validate([
         
                'name'=> 'required',
                'title'=> 'required',
                'placeholder'=> 'required',
                'number'=> 'required|integer',
                'type'=> 'required|in:input,select,texstarea,checkbox,file',
                'options'=> 'nullable',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $options = json_encode($request['options']);

        $reportsurveryfiled->update([
            'name'=> $request['name'],
            'title'=> $request['title'],
            'placeholder'=> $request['placeholder'],
            'type'=> $request['type'],
            'number'=> $request['number'],
            'options'=>$options,

        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$reportsurveryfiled]], 200);
    }

    public function destroy(CaseReportSurveryField $reportsurveryfiled){
        $reportsurveryfiled->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }

}
