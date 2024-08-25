<?php

namespace App\Http\Controllers\V1\Admin\Cases;

use App\Http\Controllers\Controller;

use App\Http\Resources\Admin\CaseViolationCollection;
use App\Http\Resources\Admin\CaseViolationResource;
use App\Models\CaseViolation;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CaseViolationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:caseViolation.index'])->only('index');
        $this->middleware(['permission:caseViolation.store'])->only('store');
        $this->middleware(['permission:caseViolation.show'])->only('show');
        $this->middleware(['permission:caseViolation.update'])->only('update');
        $this->middleware(['permission:caseViolation.destroy'])->only('destroy');
    }
    public function index(Request $request) {
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
        $caseViolations = CaseViolation::paginate($perPage);
        return new CaseViolationCollection($caseViolations);
    }
    
    public function show(CaseViolation $violation){
        return new CaseViolationResource($violation);
    }

    public function store(Request $request){
        // $validated = $request->validate([
        //     'id' =>'required',
        //     'name'=>'required',
        //     'title'=>'required',
        //     'placeholder'=>'required',
        //     'type'=>'required',
        //     'number'=> 'required',
        // ]);
        try {
            $validated = $request->validate([
                'title'=>'required',
                'user_id'=> 'required|integer|exist:users,id',
                'case_request_id'=> 'required|integer|exist:case_requests,id',

          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $CaseViolation=CaseViolation::create([
            'user_id'=>$request['user_id'],
            'case_request_id'=>$request['case_request_id'],
            'title'=>$request['title'],
            'description'=>$request['description'],
           ]);
           return response()->json(['success' => true, 'message' => 'true','data'=>[$CaseViolation]], 200);
    }

    public function update(Request $request,CaseViolation $violation){
        try {
            $validated = $request->validate([
                'title'=>'required',


                'user_id'=> 'required|integer|exist:users,id',
                'case_request_id'=> 'required|integer|exist:case_requests,id',

                

          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $violation->update([
            'user_id'=>$request['user_id'],
            'case_request_id'=>$request['case_request_id'],
            'title'=>$request['title'],
            'description'=>$request['description'],

        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$violation]], 200);
    }

    public function destroy(CaseViolation $violation){
        $violation->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }

}
