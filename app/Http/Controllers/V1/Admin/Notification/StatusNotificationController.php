<?php

namespace App\Http\Controllers\V1\Admin\Notification;
use App\Http\Controllers\Controller;

use App\Http\Resources\Admin\CouponCollection;
use App\Http\Resources\Admin\CouponResource;
use App\Http\Resources\Admin\StatusNotificationCollection;
use App\Http\Resources\Admin\StatusNotificationResource;
use App\Http\Resources\Panel\CurrencyCollection;
use App\Http\Resources\Panel\CurrencyResource;
use App\Models\Currency;
use App\Models\FinancialAccount;
use App\Models\StatusNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class StatusNotificationController extends Controller
{

    public function index(){
        $statusNotifications=StatusNotification::all();
        return new StatusNotificationCollection($statusNotifications);
}
public function show(StatusNotification $statusNotification){
    return new StatusNotificationResource($statusNotification);

}


public function update(Request $request,StatusNotification $statusNotification){
  
    $statusNotification->update([
        'name_fa'=>$request['name_fa'],
        'title'=>$request['title'],
        'description'=>$request['description'],
        'sms'=>$request['sms'],
        'email'=>$request['email'],
        'fcm'=>$request['fcm'],
        'app'=>$request['app'],

    ]);

    return response()->json(['success' => true, 'message' => 'true','data'=>[$statusNotification]], 200);
}


    
  


}







