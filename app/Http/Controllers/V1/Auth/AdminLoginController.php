<?php

namespace App\Http\Controllers\V1\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AdminLoginController extends Controller
{


    public function login(Request $request){
        // dd(4);
        // $validated = $request->validate([
        //     'phone' => 'required',
        //     'password' => 'required'
        // ]);
        $user=User::where('phone',$request->phone)->firstorfail();
        if (!$user){
            // response false user after enter password
            return response()->json(['success' => false, 'message' => 'not exist user'], 401);
        }
        if (! $user->hasRole('admin')){
            // response false user after enter password
            return response()->json(['success' => false, 'message' => 'no role user'], 401);
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
            return response()->json(['success' => true, 'message' => 'true','data'=>$data], 200);
        }
    }



    }

    public function checkotp(Request $request){
        $validated = $request->validate([
            'mobile' => 'required|ir_mobile:zero',
            'code'=>'required',
        ]);

        $user=User::where('mobile',$request->mobile)->first();
        $otp=Otp::where('mobile',$request->mobile)->first();
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
            $otp->delete();
            $token = $user->createToken($request->server('HTTP_USER_AGENT'));
            $user->update([
                'push_token'=>$request->push_token
            ]);
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
          return response()->json($data, Response::HTTP_OK);

        }
//return response


    }
    public function logout(){


        $user = Auth::guard('sanctum')->user();


        if ($user) {
            $user->tokens()->delete();
        }
        return response()->json($user, true);

    }
    // public function getSessionUser(Request $request){

    //     }
    }










