<?php

namespace App\Http\Controllers\V1\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; 
use Illuminate\Auth\Events\Verified;
use App\Mail\Verify;
use Illuminate\Support\Facades\Mail;

class VerifyController extends Controller
{


    public function verify(Request $request,$id,$hash){
        $user = User::find($id);

       
        if (!$user || sha1($user->getEmailForVerification()) !== $hash) {
            abort(404); 
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return response()->json('message', 'Verification link sent!');
    }


    public function requestVerify(Request $request){
        $id=$request->user()->id;
        $user = User::find($id);
        if($user->email && !$user->email_verified_at){
            Mail::to($user->email)->send(new Verify($user));
    
        }
        if($user->email_verified_at){
            return response()->json(['success' => false, 'message' => 'before email verfied '], 200);

        }
        return response()->json(['success' => true, 'message' => 'send email'], 200);

      
    }
  

    }


    // public function getSessionUser(Request $request){

    //     }
    










