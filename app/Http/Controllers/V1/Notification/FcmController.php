<?php

namespace App\Http\Controllers\V1\Notifiacation;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class FcmController extends Controller
{



    public static function sendGeneral($title,$description,$users,$user_id)
    {
        $FIREBASE_TOKEN=env("FIREBASE_TOKEN",'09382583970');

        if($users == 'all'){
            $users = User::all();
            $allFcmTokens = [];

            foreach ($users as $user) {
                $allFcmTokens[] = $user->fcmTokens;
            }
        }
        if($users == 'user'){
            $users = User::role('user')->get();
                        $allFcmTokens = [];

            foreach ($users as $user) {
                $allFcmTokens[] = $user->fcmTokens;
            }
        }
        if($users == 'expert'){
            $users = User::role('expert')->get();
                        $allFcmTokens = [];

            foreach ($users as $user) {
                $allFcmTokens[] = $user->fcmTokens;
            }
        }
        if($user_id){
            $user = User::find($user_id); // Assuming $userId is the ID of the user you want to retrieve FCM tokens for
$allFcmTokens = $user->fcmTokens;
        }
        $url = 'https://fcm.googleapis.com/fcm/send';
        

        $serverKey = $FIREBASE_TOKEN;

        $data = [
            "push_token" => $allFcmTokens,
            "notification" => [
                "title" => $title,
                "desctiption" => $description,
             
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

    public static function sendDefault($title,$description,$user_id)
    {
      
        if($user_id){
            $user = User::find($user_id); // Assuming $userId is the ID of the user you want to retrieve FCM tokens for
            $allFcmTokens = $user->fcmTokens;
        }
        $url = 'https://fcm.googleapis.com/fcm/send';
        $FIREBASE_TOKEN=env("FIREBASE_TOKEN",'09382583970');
        

        $serverKey = $FIREBASE_TOKEN;

        $data = [
            "push_token" => $allFcmTokens,
            "notification" => [
                "title" => $title,
                "desctiption" => $description,
             
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







