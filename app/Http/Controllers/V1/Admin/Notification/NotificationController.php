<?php

namespace App\Http\Controllers\V1\Admin\Notification;
use App\Http\Controllers\Controller;
use App\Http\Controllers\V1\Notifiacation\FcmController;
use App\Http\Controllers\V1\Notification\SmsController;
use App\Http\Resources\Admin\CouponCollection;
use App\Http\Resources\Admin\CouponResource;

use App\Http\Resources\Panel\CurrencyCollection;
use App\Http\Resources\Panel\CurrencyResource;
use App\Models\Currency;
use App\Models\FinancialAccount;
use App\Models\Role;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class NotificationController extends Controller
{

    public function send(Request $request){
        
        $types=$request['type']; //$types=['fcm','sms']
        // SmsController::sendGeneral('9394889350','oi','ooooo');
       if($request->user_id){ 
        $users=User::findOrFail($request->user_id);
        $users->notify(new GeneralNotification($users,$request));        
       }
       if($request->user == 'user'){ 
        $users = User::role('user')->get();
        foreach ($users as $user) {
            $user->notify(new GeneralNotification($user,$request));        
        }
       }
       if($request->user == 'expert'){ 
        $experts = User::role('expert')->get();
      
        foreach ($experts as $user) {
            $user->notify(new GeneralNotification($user,$request));        
        }
       }

       if($request->user == 'all'){ 
        $users=User::all();
        foreach ($users as $user) {
            $user->notify(new GeneralNotification($user,$request));        
        }
       }
       if (in_array("sms", $request['type'])) {
        $phones=$users->pluck('phone');
       SmsController::sendGeneral($phones, $request->title, $request->description);
       }
       if (in_array("fcm", $request['type'])) {
        // $title,$description,$users,$user_id
        FcmController::sendGeneral($request->title, $request->description,$request->user,$request->user_id);
       }

    
  
    }



    
    public static function sendWebNotification($title,$description)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        // $FcmToken = User::whereNotNull('push_token')->pluck('push_token')->all();

        $serverKey = 'AAAAm1kgGVI:APA91bFKr42HavkJuyN-U1Q0gBBYMf2vRtVLsdqFuxjSh-BFF4fL5498Q8iZGKV3ciQnu6TsCkJhsPlosTLvuAwknraebRVnehSlNEE24wMzUJssrY2dU0eHcwZmWZmCYKPuRxU4FWVG';

        $data = [
            "notification" => [
                "title" =>$title,
                "body" => $description,

            ],
            "to"=> "/topics/global",
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
    }
    public function sendCustomWebNotification(Request $request)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $users=User::where('group_id', $request['group_id'])->get();
        $FcmToken = $users->whereNotNull('push_token')->pluck('push_token')->all();

        $serverKey = 'AIzaSyDLCOd5JnTyVLVd7LfFA09o9tiiEIMDlSw';

        $data = [
            "push_token" => $FcmToken,
            "notification" => [
                "title" => $request['tittle'],
                "desctiption" => $request['description'],
                "send_date"=>$request['send_date'],
                "src"=>$request['src']
            ]
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        dd($result);
    }

}







