<?php

namespace App\Http\Controllers\V1\Admin\Coupon;
use App\Http\Controllers\Controller;

use App\Http\Resources\Admin\CouponCollection;
use App\Http\Resources\Admin\CouponResource;
use App\Models\CaseGroup;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:coupon.index'])->only('index');
        $this->middleware(['permission:coupon.store'])->only('store');
        $this->middleware(['permission:coupon.show'])->only('show');
        $this->middleware(['permission:coupon.update'])->only('update');
        $this->middleware(['permission:coupon.destroy'])->only('destroy');

    }

    public function index(Request $request)
{
    $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
    $sortBy = $request->query('sort_by', 'id'); // Default to sorting by 'id' if not specified
    $coupones = Coupon::orderBy($sortBy, 'desc')->paginate($perPage);
    return new CouponCollection($coupones);
}
    public function show(Coupon $coupon){
        return new CouponResource($coupon);

    }

    public function store(Request $request){
       
        try {
            $validated = $request->validate([
                'end_date'=> 'required',
                'case_group_id'=> 'required|integer|exist:case_groups,id',
                'case_category_id'=> 'required|integer|exist:case_categories,id',


                'count'=> 'required|integer',
                'code'=> 'required',
                'description'=> 'required',
                'discount'=> 'required|number',
                'type'=> 'required|in:static,percent',
                'filter_user'=> 'required',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $coupon=Coupon::create([

            'end_date'=>$request['end_date'],
            'case_category_id'=>$request['case_category_id'],
            'case_group_id'=>$request['case_group_id'],
            'count'=>$request['count'],
            'code'=>$request['code'],
            'description'=>$request['description'],
            'discount'=>$request['discount'],
            'type'=>$request['type'],
            'filter_user'=>$request['filter_user'],



        ]);
        $coupon->users()->sync($request['users']);
        return response()->json(['success' => true, 'message' => 'true','data'=>[$coupon]], 200);
    }

    public function update(Request $request,Coupon $coupon){
        try {
            $validated = $request->validate([
                'end_date'=> 'required',
                'case_group_id'=> 'required|integer|exist:case_groups,id',
                'case_category_id'=> 'required|integer|exist:case_categories,id',


                'count'=> 'required|integer',
                'code'=> 'required',
                'description'=> 'required',
                'discount'=> 'required|number',
                'type'=> 'required|in:static,percent',
                'filter_user'=> 'required',
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $coupon->update([
            'end_date'=>$request['end_date'],
            'case_category_id'=>$request['case_category_id'],
            'case_group_id'=>$request['case_group_id'],
            'count'=>$request['count'],
            'code'=>$request['code'],
            'description'=>$request['description'],
            'discount'=>$request['discount'],
            'type'=>$request['type'],
            'filter_user'=>$request['filter_user'],

        ]);
        $coupon->users()->detach();
        $coupon->users()->sync($request['users']);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$coupon]], 200);
    }
    public function destroy(Coupon $coupon){
        $coupon->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }
    }







