<?php

namespace App\Http\Controllers\V1\Admin\Cases;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CaseCategoryFiledCollection;
use App\Http\Resources\Admin\CaseCategoryFiledResource;
use App\Models\CaseCategory;
use App\Models\CaseCategoryAnimal;
use App\Models\CaseCategoryField;
use App\Models\CaseGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CaseCategoryFiledController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:caseCategoryFiled.index'])->only('index');
        $this->middleware(['permission:caseCategoryFiled.store'])->only('store');
        $this->middleware(['permission:caseCategoryFiled.show'])->only('show');
        $this->middleware(['permission:caseCategoryFiled.update'])->only('update');
        $this->middleware(['permission:caseCategoryFiled.destroy'])->only('destroy');
    }
    public function index(Request $request){
        if($request->query('animal_id')){
        $caseCategoryFields=CaseCategoryField::where('case_category_animal_id',$request->query('animal_id'))->get();

        }
        else{
            $caseCategoryFields=CaseCategoryField::all();

        }
        return new CaseCategoryFiledCollection($caseCategoryFields);
    }
    public function show(CaseCategoryField $categoryFiled){
        return new CaseCategoryFiledResource($categoryFiled);

    }

    public function store(Request $request){
        try {
            $validated = $request->validate([
                'case_category_animal_id'=> 'required|integer|exist:case_category_animals,id',
                'name'=> 'required',
                'title'=> 'required',
                'placeholder'=> 'required',
                'index'=> 'required|integer',
                'type'=> 'required|in:input,select,texstarea,checkbox,file',
                'options'=> 'nullable',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
    
        $options = json_encode($request['options']);
        $caseCategoryFiled=CaseCategoryField::create([
            'case_category_animal_id'=>$request['case_category_animal_id'],
            'name'=>$request['name'],
            'title'=>$request['title'],
            'placeholder'=>$request['placeholder'],
            'index'=>$request['index'],
            'type'=>$request['type'],
            'options'=>$options,

        ]);
        return response()->json(['success' => true, 'message' => 'true','data'=>[$caseCategoryFiled]], 200);
    }

    public function update(Request $request,CaseCategoryField $categoryFiled){
        try {
            $validated = $request->validate([
                'case_category_animal_id'=> 'required|integer|exist:case_category_animals,id',
                'name'=> 'required',
                'title'=> 'required',
                'placeholder'=> 'required',
                'index'=> 'required|integer',
                'type'=> 'required|in:input,select,texstarea,checkbox,file',
                'options'=> 'nullable',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $options = json_encode($request['options']);

        $categoryFiled->update([
            'case_category_animal_id'=>$request['case_category_animal_id'],
            'name'=>$request['name'],
            'title'=>$request['title'],
            'placeholder'=>$request['placeholder'],
            'index'=>$request['index'],
            'type'=>$request['type'],
            'options'=>$options,

        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$categoryFiled]], 200);
    }
    public function destroy(CaseCategoryField $categoryFiled){
        $categoryFiled->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }
    }







