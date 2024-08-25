<?php

namespace App\Http\Controllers\V1\Panel\Notifiacation;
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

class NotifiacationController extends Controller
{



    public function index(Request $request)
    {
        // dd(9);
        $user_id = $request->user()->id;
        $perPage = $request->query('per_page', 10); // Default to 10 items per page if not specified
    
        $notifications = Notification::where('user_id', $user_id)->paginate($perPage);
    
        return new NotificationCollection($notifications);
    }
    public function show(Request $request,Notification $notification){
             return new NotificationResource($notification);
     
         }
 
    }







