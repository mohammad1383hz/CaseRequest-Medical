<?php

namespace App\Http\Controllers\V1\Admin\Cases;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CaseGroupCollection;
use App\Http\Resources\Admin\CaseGroupResource;
use App\Models\CaseCategory;
use App\Models\CaseGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CaseGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:caseGroup.index'])->only('index');
        $this->middleware(['permission:caseGroup.store'])->only('store');
        $this->middleware(['permission:caseGroup.show'])->only('show');
        $this->middleware(['permission:caseGroup.update'])->only('update');
        $this->middleware(['permission:caseGroup.destroy'])->only('destroy');
    }
    public function index(Request $request) {
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
        $caseGroupes = CaseGroup::paginate($perPage);
        return new CaseGroupCollection($caseGroupes);
    }
    
    public function show(CaseGroup $Group){
        return new CaseGroupResource($Group);
    }

    public function store(Request $request){
        try {
            $validated = $request->validate([
                'parent_id'=> 'nullable',
            'title'=> 'required',
            'description'=> 'nullable',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
            
        $caseGroup=CaseGroup::create([
            'title'=>$request['title'],
            'description'=>$request['description'],
            'parent_id'=>$request['parent_id'],
        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$caseGroup]], 200);
    }

    public function update(Request $request,CaseGroup $Group){
        try {
            $validated = $request->validate([
                'parent_id'=> 'nullable',
            'title'=> 'required',
            'description'=> 'nullable',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $Group->update([
            'title'=>$request['title'],
            'description'=>$request['description'],
            'parent_id'=>$request['parent_id'],
        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$Group]], 200);
    }

    public function destroy(CaseGroup $Group){
        $Group->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }


    }







