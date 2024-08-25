<?php

namespace App\Http\Controllers\V1\Admin\Cases;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CaseCategoryAnimalCollection;
use App\Http\Resources\Admin\CaseCategoryAnimalResource;
use App\Models\CaseCategory;
use App\Models\CaseCategoryAnimal;
use App\Models\CaseGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CaseCategoryAnimalController extends Controller
{
    public function __construct()
    {
          $this->middleware(['permission:caseCategoryAnimal.index'])->only('index');
        $this->middleware(['permission:caseCategoryAnimal.store'])->only('store');
        $this->middleware(['permission:caseCategoryAnimal.show'])->only('show');
        $this->middleware(['permission:caseCategoryAnimal.update'])->only('update');
        $this->middleware(['permission:caseCategoryAnimal.destroy'])->only('destroy');
    }


public function index(Request $request) {
    $perPage = $request->query('per_page', 10);
    $caseCategoryAnimals = CaseCategoryAnimal::paginate($perPage);
    return new CaseCategoryAnimalCollection($caseCategoryAnimals);
}

    public function show(CaseCategoryAnimal $categoryAnimal){
        return new CaseCategoryAnimalResource($categoryAnimal);

    }

    public function store(Request $request){
      
        try {
            $validated = $request->validate([
                'case_category_id' => 'required|integer|exists:case_categories,id',

                'title'=> 'required',
                'description'=> 'required',
                'price'=> 'required',
                'commission_type'=> 'required|in:fixed,percent',
                'commission_value'=> 'required|integer',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $caseCategoryAnimal=CaseCategoryAnimal::create([
            'case_category_id'=>$request['case_category_id'],
            'title'=>$request['title'],
            'description'=>$request['description'],
            'price'=>$request['price'],
            'commission_type'=>$request['commission_type'],
            'commission_value'=>$request['commission_value'],
        ]);
        return response()->json(['success' => true, 'message' => 'true','data'=>[$caseCategoryAnimal]], 200);
    }

    public function update(Request $request,CaseCategoryAnimal $categoryAnimal){
        try {
            $validated = $request->validate([
                'case_category_id' => 'required|integer|exists:case_categories,id',
                'title'=> 'required',
                'description'=> 'required',
                'price'=> 'required|integer',
                'commission_type'=> 'required|in:fixed,percent',
                'commission_value'=> 'required|integer',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $categoryAnimal->update([
            'case_category_id'=>$request['case_category_id'],
            'title'=>$request['title'],
            'description'=>$request['description'],
            'price'=>$request['price'],
            'commission_type'=>$request['commission_type'],
            'commission_value'=>$request['commission_value'],
        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$categoryAnimal]], 200);
    }
    public function destroy(CaseCategoryAnimal $categoryAnimal){
        $categoryAnimal->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }

    }
