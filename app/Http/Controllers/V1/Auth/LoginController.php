<?php

namespace App\Http\Controllers\V1\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\V1\Notification\SmsController;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; 

class LoginController extends Controller
{


    public function login(Request $request){
        // dd(4);
        $validated = $request->validate([
            'phone' => 'required',
            'password' => 'required',
            'country_id' => 'required',

        ]);
        $user = User::where('phone', $request->phone)->first();

// If the user does not exist with the provided phone number, check by email
    if (!$user) {
        $user = User::where('email', $request->email)->first();

        // If a user exists with the provided email and it's not verified
        if ($user && !$user->is_email_verified) {  
            return response()->json(['success' => false, 'message' => 'Email not verified'], 401);
        }
    }
        if (!$user){
            // response false user after enter password
            return response()->json(['success' => false, 'message' => 'not exist user'], 401);
        }
        if ($user->country_id != $request->country_id){
            // response false user after enter password
            return response()->json(['success' => false, 'message' => 'not exist user'], 401);
        }
        if ($user->is_phone_verified){
            // response false user after enter password
            return response()->json(['success' => false, 'message' => 'not is_phone_verified'], 401);
        }
        if ($user->is_blocked){
            // response false user after enter password
            return response()->json(['success' => false, 'message' => 'is_blocked'], 401);
        }
        else{
           if(! Hash::check($request->password,$user->password)){
            return response()->json(['success' => false, 'message' => 'not correct password'], 401);
               // response false user after enter password
        }
        else{
            $token = $user->createToken($request->server('HTTP_USER_AGENT'));
            $data = [
                'token' => $token->plainTextToken,
               'permissions' => $user->getPermissionNames(),
               'roles' => $user->getRoleNames(),
            ];
           
            DB::table('sesions')->insert([
                'user_id' => $user->id,
                'ip_adress' => $request->ip(),
                'user_agent'=> $request->header('User-Agent'),
                // 'logged_in_by'=> $request->header('User-Agent'),


            ]);
           
            return response()->json(['success' => true, 'message' => 'true','data'=>$data], 200);
        }
    }



    }

    public function checkotp(Request $request){
        $validated = $request->validate([
            'phone' => 'required',
            'code'=>'required',
            'country_id'=> 'required',
        ]);

        $user=User::where('phone',$request->phone)->first();
        $otp=Otp::where('phone',$request->phone)->first();
        if(!$otp){
            return response()->json(['success' => false, 'message' => 'Unauthorized otp code'],200);
        }
        if($otp->code !==$request->code){
            return response()->json(['success' => false, 'message' => 'Unauthorized otp code'],200);
        }
        if (!$user && $otp->code==$request->code){
            return response()->json(['success' => true, 'message' => 'not exist user redirect register user'], 200);
        }
        if ($user){
            if ($user->is_blocked){
                // response false user after enter password
                return response()->json(['success' => false, 'message' => 'is_blocked'], 401);
            }  
            $otp->delete();
            $token = $user->createToken($request->server('HTTP_USER_AGENT'));
          
            //            $token->accessToken->ip = $request->json()->get('ip');
            //            $token->accessToken->platform = $request->json()->get('platform');
            //            $token->accessToken->save();
            //
                        $data = [
                            'token' => $token->plainTextToken,
                           'user' => $user,
            //                'roles' => auth()->user()->getRoleNames(),
                        ];
            //            $user=auth()->attempt($validated);
            return response()->json(['success' => true, 'message' => 'true','data'=>$data], 200);

        }
//return response


    }

    public function loginOtp(Request $request){
        $validated = $request->validate([
            'phone' => 'required',
            'counrty_id' => 'required'

        ]);
        $user=User::where('phone',$request->phone)->first();
        if (!$user){
            return response()->json(['success' => false, 'message' => 'user_not_exist'], 200);
        }
        if ($user->country_id != $request->country_id){
            // response false user after enter password
            return response()->json(['success' => false, 'message' => 'not exist user'], 401);
        }
        if ($user){

           
            $otp=Otp::where('phone',$request->phone)->delete();
            $otp = rand(10000, 99999);
            $token=SmsController::send($request->phone,$otp);
    
            Otp::create([
            'code'=>$otp,
            'phone'=>$request->phone,
            ]);
            return response()->json(['success' => false, 'message' => 'user_exist'], 200);

         }
      
      
       
    }

    public function logout(){


        $user = Auth::guard('sanctum')->user();


        if ($user) {
            $user->tokens()->delete();
        }
        return response()->json($user, true);

    }
    public function updateFcm(Request $request){
        DB::table('fcm_user')->insert([
            'user_id' => $request->user()->id,
            'device_token' => $request->device_token
        ]);
        return response()->json(['success' => true, 'message' => 'true','data'=>null], 200);

    }
    // public function getSessionUser(Request $request){

    //     }
    }










