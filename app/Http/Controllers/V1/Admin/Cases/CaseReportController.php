<?php

namespace App\Http\Controllers\V1\Admin\Cases;

use App\Http\Controllers\Controller;

use App\Http\Resources\Admin\CaseReportCollection;
use App\Http\Resources\Admin\CaseReportResource;
use App\Models\CaseFile;
use App\Models\CaseReport;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CaseReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:caseReport.index'])->only('index');
        $this->middleware(['permission:caseReport.store'])->only('store');
        $this->middleware(['permission:caseReport.show'])->only('show');
        $this->middleware(['permission:caseReport.update'])->only('update');
        $this->middleware(['permission:caseReport.destroy'])->only('destroy');
    }
    public function index(Request $request) {
        $perPage = $request->query('per_page', 10); 
        $tech = $request->query('tech');
        $interpretation = $request->query('interpretation');
        $diagnosis = $request->query('diagnosis');
        $comment = $request->query('comment');
        $query = CaseReport::all();
        if ($tech) {
            $query->where('tech', $tech);
        }
        if ($interpretation) {
            $query->where('interpretation', $interpretation);
        }
        if ($diagnosis) {
            $query->where('diagnosis', $diagnosis);
        }
        if ($comment) {
            $query->where('comment', $comment);
        }
    
        $caseReports = $query->paginate($perPage);
        return new CaseReportCollection($caseReports);
    }
    public function show(CaseReport $report){
        return new CaseReportResource($report);
    }

    public function store(Request $request){
    
        try {
            $validated = $request->validate([
                'user_id'=> 'required',
                'case_score'=> 'nullable',
                'report_score'=> 'nullable',
                'tech'=> 'required',
                'interpretation'=> 'required',
                'diagnosis'=> 'required',
                'comment'=> 'required',
                'case_assignment_id'=> 'required',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $CaseReport=CaseReport::create([
            'user_id'=>$request['user_id'],
            'case_assignment_id'=> $request['case_assignment_id'],
            'case_score'=> $request['case_score'],

            'report_score'=> $request['report_score'],
            'tech'=> $request['tech'],
            'interpretation'=> $request['interpretation'],
            'diagnosis'=> $request['diagnosis'],
            'comment'=> $request['comment'],

           ]);

           if($request->file('file')) {
            foreach($request->file('file') as $file){
                $document = new File;

                $file_name = time().'_'.'report'.$file->getClientOriginalName();
                $file_path = $file->storeAs('case_report_file', $file_name, 'public');
                $path='storage/'.$file_path;
                $document->src=$path;
                $document->name=$file_name;
                $document->type='file';
                $document->parent_id=7;
                $document->save();
                $caseFile = new CaseFile;
                $caseFile->file_id =$document->id;
                $caseFile->case_report_id=$CaseReport->id;
                $caseFile->save();

                }
        }
           return response()->json(['success' => true, 'message' => 'true','data'=>[$CaseReport]], 200);
    }

    public function update(Request $request,CaseReport $report){
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'case_score'=> 'nullable',
                'report_score'=> 'nullable',
                'tech'=> 'required',
                'interpretation'=> 'required',
                'diagnosis'=> 'required',
                'comment'=> 'required',
                'case_assignment_id' => 'required|integer|exists:case_assignments,id',

          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $report->update([
            'user_id'=>$request['user_id'],
            'case_assignment_id'=> $request['case_assignment_id'],
            'case_score'=> $request['case_score'],

            'report_score'=> $request['report_score'],
            'tech'=> $request['tech'],
            'interpretation'=> $request['interpretation'],
            'diagnosis'=> $request['diagnosis'],
            'comment'=> $request['comment'],

        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$report]], 200);
    }

    public function destroy(CaseReport $report){
        $report->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }

}
