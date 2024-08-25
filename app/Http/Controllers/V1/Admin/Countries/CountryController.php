<?php

namespace App\Http\Controllers\V1\Admin\Countries;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CountryCollection;
use App\Http\Resources\Admin\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CountryController extends Controller
{
    // public function __construct()
    // {
    //     // $this->middleware('role.expert');
    // }
    
     
public function index(Request $request)
{
    $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
    $countries = Country::paginate($perPage);
    return new CountryCollection($countries);
}
    public function show(Country $country){
        return new CountryResource($country);

    }

    public function store(Request $request){
        // $validated = $request->validate([

        //     // 'user_id'=> $this->user_id,
        //     'end_date'=> 'required',
        //     'case_category_id'=> 'required',
        //     'case_group_id'=> 'required',
        //     'count'=> 'required',
        //     'code'=> 'required',
        //     'description'=> 'required',
        //     'discount'=> 'required',
        //     'type'=> 'required',


        // ]);
        try {
            $validated = $request->validate([
                'symbol'=> 'required',
                'calling_code'=> 'required',
                'currency_id'=> 'required',
                'name'=> 'required',


          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $country=Country::create([

            'symbol'=>$request['symbol'],
            'calling_code'=>$request['calling_code'],
            'currency_id'=>$request['currency_id'],

            'name'=>$request['name'],
          

        ]);
        return response()->json(['success' => true, 'message' => 'true','data'=>[$country]], 200);
    }
    public function import(Request $request){
        // $validated = $request->validate([

        //     // 'user_id'=> $this->user_id,
        //     'end_date'=> 'required',
        //     'case_category_id'=> 'required',
        //     'case_group_id'=> 'required',
        //     'count'=> 'required',
        //     'code'=> 'required',
        //     'description'=> 'required',
        //     'discount'=> 'required',
        //     'type'=> 'required',


        // ]);
        foreach ($request['countries'] as $country){
            Country::create([

                'symbol'=>$country['currencySymbol'],
                'calling_code'=>$country['callingCode'],
                'currency_id'=>1,
                'name'=>$country['name'],
              
    
            ]);
        }
        $iran=Country::where('id',109)->first();
        $iran->update([
            'currency_id'=>2,

        ]);
      
        return response()->json(['success' => true, 'message' => 'true','data'=>[$country]], 200);
    }
    public function update(Request $request,Country $country){
        try {
            $validated = $request->validate([
                'symbol'=> 'required',
                'calling_code'=> 'required',
                'currency_id'=> 'required',
                'name'=> 'required',


          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $country->update([
            'symbol'=>$request['symbol'],
            'calling_code'=>$request['calling_code'],
            'name'=>$request['name'],
            'currency_id'=>$request['currency_id'],


          
        ]);
     
        return response()->json(['success' => true, 'message' => 'true','data'=>[$country]], 200);
    }
    public function destroy(Country $country){
        $country->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }
    }







