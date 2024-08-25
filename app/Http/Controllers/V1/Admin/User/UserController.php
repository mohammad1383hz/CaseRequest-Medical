<?php

namespace App\Http\Controllers\V1\Admin\User;
use App\Http\Controllers\Controller;

use App\Http\Resources\Admin\UserCollection;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:user.index'])->only('index');
        $this->middleware(['permission:user.store'])->only('store');
        $this->middleware(['permission:user.show'])->only('show');
        $this->middleware(['permission:user.update'])->only('update');
        $this->middleware(['permission:user.destroy'])->only('destroy');

    }
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
        $query = User::query();
        
        // Filter by last_name, country_id, phone
        $last_name = $request->query('last_name');
        $country_id = $request->query('country_id');
        $phone = $request->query('phone');
        
        if ($last_name) {
            $query->where('last_name', $last_name);
        }
        
        if ($country_id) {
            $query->where('country_id', $country_id);
        }
        
        if ($phone) {
            $query->where('phone', $phone);
        }
        
        // Sorting
        $sortBy = $request->query('sort_by');
        $sortDirection = $request->query('sort_direction', 'desc');
        
        if ($sortBy && in_array($sortBy, ['case_score', 'report_score', 'time_response_score'])) {
            $query->leftJoin('case_assignments', 'users.id', '=', 'case_assignments.user_id')
                  ->leftJoin('case_reports', 'case_assignments.id', '=', 'case_reports.case_assignment_id')
                  ->select('users.*', DB::raw("AVG(case_reports.{$sortBy}) AS {$sortBy}"))
                  ->groupBy('users.id');
        
            // Add other selected columns from the users table to the GROUP BY clause
            $query->groupBy('users.first_name', 'users.last_name', 'users.country_id', 'users.phone','users.email','users.password','users.is_phone_verified','users.avatar_file_id',
            'users.src_national_cart',
            'users.src_img_1',
            'users.src_img_2',
            'users.status',
            'users.city',
            'users.language',
            'users.is_active',
            'users.is_blocked',
            'users.last_login_at',
            'users.last_login_ip',
            'users.email_verified_at',
            'users.group_id',
            'users.country_id',
            'users.currency_id',   
            'users.created_at',    
            'users.updated_at',    
            );
            
            $query->orderBy($sortBy, $sortDirection);
        }
        $users = $query->paginate($perPage);
        
        return new UserCollection($users);
    }
    
    
    public function show(User $user){
        return new UserResource($user);

    }

    public function store(Request $request){
        try {
            $validated = $request->validate([
                'first_name'=> 'required',
                'last_name'=> 'required',
                'email'=> 'nullable',
                'phone'=> 'required',
                'password'=> 'required',
                'city'=> 'required',


            // 'group_id'=> 'required',
            'currency_id'=> 'required',
            'country_id'=> 'required',

               
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
     
        $user=User::create([
            'first_name'=>$request['first_name'],
            'last_name'=>$request['last_name'],
            'email'=>$request['email'],
            'phone'=>$request['phone'],
            'city'=>$request['city'],
            'group_id'=>$request['group_id'],
            'currency_id'=>$request['currency_id'],
            'country_id'=>$request['country_id'],

            'password' => Hash::make($request['password'])


        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$user]], 200);
    }

    public function update(Request $request,User $user){
        try {
            $validated = $request->validate([
                'first_name'=> 'required',
                'last_name'=> 'required',
                'email'=> 'nullable',
                'phone'=> 'required',
                'password'=> 'required',
                'city'=> 'required',


            // 'group_id'=> 'required',
            'currency_id'=> 'required',
            'country_id'=> 'required',

               
          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['errors' => $e->validator->errors()], 422);
          }
        $user->update([
            'first_name'=>$request['first_name'],
            'last_name'=>$request['last_name'],
            'email'=>$request['email'],
            'phone'=>$request['phone'],
            'city'=>$request['city'],
            'group_id'=>$request['group_id'],
            'currency_id'=>$request['currency_id'],
            'country_id'=>$request['country_id'],

            'password' => Hash::make($request['password'])
        ]);

        return response()->json(['success' => true, 'message' => 'true','data'=>[$user]], 200);
    }
    public function destroy(User $user){
        $user->delete();
        return response()->json(['success' => true, 'message' => 'true','data'=>[]], 200);

    }
    public function getNumberUser(){
        $userCount = User::count();

        return response()->json(['success' => true, 'message' => 'true','data'=>$userCount], 200);

    }
    public function showMe(Request $request){
        $user_id=$request->user()->id;
        $user=User::where('id',$user_id)->first();

        // dd($userAverageScore);
        return new UserResource($user);

    }

    public function changeStatusUser(Request $request,User $user){
        if($request->status == 'block'){
            $user->update([
                'is_blocked'=>Carbon::now(),
                'is_active'=>null,
                
            ]);
            $user = Auth::guard('sanctum')->user();


            if ($user) {
                $user->tokens()->delete();
            }
        }
        if($request->status == 'active'){
            $user->update([
                'is_active'=>Carbon::now(),
                'is_blocked'=>null,
            ]);
        }
        if($request->status == 'not_block'){
            $user->update([
                'is_blocked'=>null,
                
            ]);
        }
        if($request->status == 'not_active'){
            $user->update([
                'is_active'=>null,
            ]);
        }
       
      

        return response()->json(['success' => true, 'message' => 'true','data'=>[$user]], 200);
    }

    }







