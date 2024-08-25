<?php

namespace App\Http\Controllers\V1\Admin\Cases;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CaseCategoryCollection;
use App\Http\Resources\Admin\CaseCategoryResource;
use App\Models\CaseCategory;
use App\Models\CaseGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CaseCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:caseCategory.index'])->only('index');
        $this->middleware(['permission:caseCategory.store'])->only('store');
        $this->middleware(['permission:caseCategory.show'])->only('show');
        $this->middleware(['permission:caseCategory.update'])->only('update');
        $this->middleware(['permission:caseCategory.destroy'])->only('destroy');

    }
    public function index(Request $request) {
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
        $caseCategories = CaseCategory::paginate($perPage);
        return new CaseCategoryCollection($caseCategories);
    }
    public function show(CaseCategory $Category){
        return new CaseCategoryResource($Category);

    }

    public function store(Request $request){
        try {
            $validated = $request->validate([
                'case_group_id'=> 'required|integer|exist:case_groups,id',
                'title'=> 'required',
                'description'=> 'nullable',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $validated = $request->validate([
          
        ]);
        $caseCategory=CaseCategory::create([
            'title'=>$request['title'],
            'description'=>$request['description'],
            'case_group_id'=>$request['case_group_id'],
        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$caseCategory]], 200);
    }

    public function update(Request $request,CaseCategory $Category){
      
        try {
            $validated = $request->validate([
                'case_group_id'=> 'required|integer|exist:case_groups,id',
                'title'=> 'required',
                'description'=> 'nullable',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $Category->update([
            'title'=>$request['title'],
            'description'=>$request['description'],
            'case_group_id'=>$request['case_group_id'],
        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$Category]], 200);
    }
    public function destroy(CaseCategory $Category){
        $Category->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }
    }







