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

class ResetPasswordController extends Controller
{

    public function checkPhone(Request $request){

        $validated = $request->validate([
    
                'phone' => 'required',
                'country_id' => 'required',

           ]);
         $user=User::where('phone',$request->phone)->first();
    
         //dd($user->pssword);
         if (!$user){
            return response()->json(['success' => false, 'message' => 'user_not_exist'], 200);
         }
         if ($user->country_id != $request->country_id){
            // response false user after enter password
            return response()->json(['success' => false, 'message' => 'not exist user'], 401);
        }
         if($request->flag=='send_sms'){
            $otp=Otp::where('phone',$request->phone)->delete();
            $otp = rand(10000, 99999);
            $token=SmsController::send($request->phone,$otp);
    
            //send sms and save code
            // $token=SmsController::getToken();
            // $token=json_decode($token);
            // $TokenKey=$token->TokenKey;
            // dd($TokenKey);
            // $data = [
            //     "body" => [
            //         "ParameterArray" => [
            //             ["Parameter" => "code", "ParameterValue" => $otp]
            //         ],
            //         "phone" => $request->mobile,
            //         "TemplateId" => "77808"
            //     ]
            // ];
            // //send sms
            // $token=SmsController::sendSms($data);
    
            Otp::create([
            'code'=>$otp,
            'phone'=>$request->phone,
            ]);
         }
       
         return response()->json(['success' => true, 'message' => 'user_exist'], 200);

        }    

    



    public function resetPassword(Request $request){
        $validated = $request->validate([
            'phone' => 'required',
            'code'=>'required',
            'password'=>'required',

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
            return response()->json(['success' => false, 'message' => 'not exist user redirect register user'], 200);
        }
        if ($user){
            if ($user->is_blocked){
                // response false user after enter password
                return response()->json(['success' => false, 'message' => 'is_blocked'], 401);
            }
            $user->update([
                'password' => Hash::make($request['password']),
            ]);
            // $token = $user->createToken($request->server('HTTP_USER_AGENT'));
            // $data = [
            //     'token' => $token->plainTextToken,
            //    'permissions' => $user->getPermissionNames(),
            //    'roles' => $user->getRoleNames(),
            // ];
            return response()->json(['success' => true, 'message' => 'true','data'=>'password updated'], 200);
        }
    }













    
    }










