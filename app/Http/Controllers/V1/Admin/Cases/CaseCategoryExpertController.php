<?php

namespace App\Http\Controllers\V1\Admin\Cases;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CaseCategoryExpertCollection;
use App\Http\Resources\Admin\CaseCategoryExpertResource;
use App\Models\CaseCategory;
use App\Models\CaseCategoryExpert;
use App\Models\CaseGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CaseCategoryExpertController extends Controller
{
    public function __construct()
    {
         $this->middleware(['permission:caseCategoryExpert.index'])->only('index');
        $this->middleware(['permission:caseCategoryExpert.store'])->only('store');
        $this->middleware(['permission:caseCategoryExpert.show'])->only('show');
        $this->middleware(['permission:caseCategoryExpert.update'])->only('update');
        $this->middleware(['permission:caseCategoryExpert.destroy'])->only('destroy');
    }
    public function index(Request $request) {
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
        $caseCategoryExperts = CaseCategoryExpert::paginate($perPage);
        return new CaseCategoryExpertCollection($caseCategoryExperts);
    }
    public function show(CaseCategoryExpert $categoryExpert){
        return new CaseCategoryExpertResource($categoryExpert);

    }

    public function store(Request $request){
        try {
            $validated = $request->validate([
                'case_category_id'=> 'required|integer|exist:case_categories,id',

                'title'=> 'required',
                'description'=> 'required',
                'refrence_index'=> 'required|integer',
                'price'=>'required|integer',
                'commission_value'=> 'required|integer',
                'commission_type'=> 'required|in:fixed,percent',
                'golden_minutes'=> 'nullable',
                'has_penalty'=> 'required',
                'penalty_type'=> 'nullable',
                'penalty_value'=> 'required',
                'penalty_time'=> 'required',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $caseCategoryExpert=CaseCategoryExpert::create([
            'case_category_id'=>$request['case_category_id'],
            'title'=>$request['title'],
            'description'=>$request['description'],
            'refrence_index'=>$request['refrence_index'],
            'price'=>$request['price'],
            'commission_type'=>$request['commission_type'],
            'commission_value'=>$request['commission_value'],
            'golden_minutes'=>$request['golden_minutes'],
            'has_penalty'=>$request['has_penalty'],
            'penalty_type'=>$request['penalty_type'],
            'penalty_value'=>$request['penalty_value'],
            'penalty_time'=>$request['penalty_time'],

        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$caseCategoryExpert]], 200);
    }

    public function update(Request $request,CaseCategoryExpert $categoryExpert){
        try {
            $validated = $request->validate([
                'case_category_id'=> 'required|integer|exist:case_categories,id',

                'title'=> 'required',
                'description'=> 'required',
                'refrence_index'=> 'required|integer',
                'price'=>'required|integer',
                'commission_value'=> 'required|integer',
                'commission_type'=> 'required|in:fixed,percent',
                'golden_minutes'=> 'nullable',
                'has_penalty'=> 'required',
                'penalty_type'=> 'nullable',
                'penalty_value'=> 'required',
                'penalty_time'=> 'required',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $categoryExpert->update([
            'case_category_id'=>$request['case_category_id'],
            'title'=>$request['title'],
            'description'=>$request['description'],
            'refrence_index'=>$request['refrence_index'],
            'price'=>$request['price'],
            'commission_type'=>$request['commission_type'],
            'commission_value'=>$request['commission_value'],
            'golden_minutes'=>$request['golden_minutes'],
            'has_penalty'=>$request['has_penalty'],
            'penalty_type'=>$request['penalty_type'],
            'penalty_value'=>$request['penalty_value'],
            'penalty_time'=>$request['penalty_time'],
        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$categoryExpert]], 200);
    }
    public function destroy(CaseCategoryExpert $categoryExpert){
        $categoryExpert->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);
    }

    }







