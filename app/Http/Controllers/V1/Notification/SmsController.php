<?php

namespace App\Http\Controllers\V1\Notification;
use App\Http\Controllers\Controller;
use App\Http\Resources\Panel\NotificationCollection;
use App\Http\Resources\Panel\NotificationResource;
use App\Mail\Verify;
use App\Models\CaseFile;
use App\Models\File;
use App\Models\FinancialAccount;
use App\Models\Notification;
use App\Models\Otp;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Melipayamak;
use Melipayamak\MelipayamakApi;

class SmsController extends Controller
{



    
    
      

            function send($phone,$otp)
            {
                // if (!$enable) return;
                $username=env("USER_SMS",'09382583970');
                $password=env("PASSWORD_SMS",'Zekshop@1401');


                $res = Http::post(
                    "https://rest.payamak-panel.com/api/SendSMS/BaseServiceNumber",
                    [
                        'username' => $username,
                        'password' => $password,
                    'to' => $phone,
                        'bodyId' => 124124,
                        "text" => $otp
                    ]);

                return $res;
            }



    public static function  sendGeneral($phone,$title,$description)
    {
        $username=env("USER_SMS",'09382583970');
        $password=env("PASSWORD_SMS",'Zekshop@1401');


        $phone=['09394889350','09153670821'];


        $data=array('username' =>$username, 'password'=>$password, 'to' =>"09394889350,09153670821", 'from' => "", "text" =>$title.".".$description);
        $post_data = http_build_query($data);
        $handle = curl_init('https://rest.payamak-panel.com/api/SendSMS/SendSMS');
        curl_setopt($handle, CURLOPT_HTTPHEADER, array(
            'content-type' => 'application/x-www-form-urlencoded'
        ));
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
        $response = curl_exec($handle);
        var_dump($response);










        $res = Http::post(
            "https://rest.payamak-panel.com/api/SendSMS/BaseServiceNumber",
            [
                'username' => $username,
                        'password' => $password,
                'to' => $phone,
                'bodyId' => 124124,

                "text" => $title."".$description,
            ]);

        return $res;
     
    }
    public static function sendDefault($phone,$bodyId )
    {
        $username=env("USER_SMS",'09382583970');
        $password=env("PASSWORD_SMS",'Zekshop@1401');
        $user=User::where("phone",$phone)->first();
        $res = Http::post(
            "https://rest.payamak-panel.com/api/SendSMS/BaseServiceNumber",
            [
                'username' => $username,
                'password' => $password,
                'to' => $phone,
                'bodyId' => $bodyId,
                "text" => $user->first_name ." ". $user->last_name,
            ]);

        return $res;
     
    }
    public function show(Request $request,Notification $notification){
             return new NotificationResource($notification);
     
         }
 
    }







