<?php

namespace App\Http\Controllers\V1\Admin\Cases;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CaseCategoryExpertCommissionCollection;
use App\Http\Resources\Admin\CaseCategoryExpertCommissionResource;
use App\Models\CaseCategory;
use App\Models\CaseCategoryExpert;
use App\Models\CaseCategoryExpertCommission;
use App\Models\CaseGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CaseCategoryExpertCommissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:caseCategoryExpertCommission.index'])->only('index');
        $this->middleware(['permission:caseCategoryExpertCommission.store'])->only('store');
        $this->middleware(['permission:caseCategoryExpertCommission.show'])->only('show');
        $this->middleware(['permission:caseCategoryExpertCommission.update'])->only('update');
        $this->middleware(['permission:caseCategoryExpertCommission.destroy'])->only('destroy');
    }
    public function index(Request $request) {
        $perPage = $request->query('per_page', 10);
        $case_expert_id = $request->query('case_expert_id');
        if ($case_expert_id) {
        $caseCategoryExpertCommissions = CaseCategoryExpertCommission::where('case_expert_id',$case_expert_id)->paginate($perPage);

        }else{
            $caseCategoryExpertCommissions = CaseCategoryExpertCommission::paginate($perPage);

        }
        return new CaseCategoryExpertCommissionCollection($caseCategoryExpertCommissions);
    }
    
    public function show(CaseCategoryExpertCommission $categoryExpertCommission){
        return new CaseCategoryExpertCommissionResource($categoryExpertCommission);

    }

    public function store(Request $request){
       
        try {
            $validated = $request->validate([
                'case_expert_id'=> 'required|integer|exist:case_categories_expert,id',
                "time_start"=> 'required',
                'time_end'=> 'required',
                'commission_value'=> 'required|integer',
                'commission_type'=> 'required|in:fixed,percent',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $caseCategoryExpertCommission=CaseCategoryExpertCommission::create([
            "case_expert_id"=>$request['case_expert_id'],
            "time_start"=>$request['time_start'],
            'time_end'=>$request['time_end'],
            'commission_value'=>$request['commission_value'],
            'commission_type'=>$request['commission_type'],
        ]);
        return response()->json(['success' => true, 'message' => 'true','data'=>[$caseCategoryExpertCommission]], 200);
    }

    public function update(Request $request,CaseCategoryExpertCommission $categoryExpertCommission){
        try {
            $validated = $request->validate([
                'case_expert_id'=> 'required|integer|exist:case_categories_expert,id',

                "time_start"=> 'required',
                'time_end'=> 'required',
                'commission_value'=> 'required|integer',
                'commission_type'=> 'required|in:fixed,percent',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $categoryExpertCommission->update([
            "case_expert_id"=>$request['case_expert_id'],
            "time_start"=>$request['time_start'],
            'time_end'=>$request['time_end'],
            'commission_value'=>$request['commission_value'],
            'commission_type'=>$request['commission_type'],
        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$categoryExpertCommission]], 200);
    }
    public function destroy(CaseCategoryExpertCommission $categoryExpertCommission){
        $categoryExpertCommission->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }
    }







