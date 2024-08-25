<?php

namespace App\Notifications;

use App\Http\Controllers\V1\Notifiacation\FcmController;
use App\Http\Controllers\V1\Notification\SmsController;
use App\Models\StatusNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Notification as ModelNotification;

class SendCaseRequestForExpert extends Notification
{
    use Queueable;

    protected $statusNotification;
    protected $user;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($user,)
    {
        $this->statusNotification = StatusNotification::where('name','SendCaseRequestForExpert')->first();
        $this->user = $user;
        
        // اینجا یک مثال است، شما می‌توانید شناسه مورد نظر خود را استفاده کنید
    }
  /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        // return ['mail'];

        if ($this->statusNotification->mail) {
            return [AppChannel::class,SmsChannel::class,FcmChannel::class,'mail'];

        }
        return [AppChannel::class,SmsChannel::class,FcmChannel::class];

      

    }
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        if ($this->statusNotification->mail) {
            return (new MailMessage)->view(
            'emails.general', ['title'=> $this->statusNotification->title,
            'description'=> $this->statusNotification->description]
        );
    }
    }
   

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
       
        return [
            //
        ];
    }
    public function toFCM(object $notifiable)
    {
        if ($this->statusNotification->fcm) {
            $title=$this->statusNotification->title;
            $description=$this->statusNotification->description;
           FcmController::sendDefault($title,$description,$this->user->id);                
        }
        // return 'fcm';

    }
    public function toSms(object $notifiable)
    {
        if ($this->statusNotification->sms) {
            $title=$this->statusNotification->title;
            $description=$this->statusNotification->description;
           SmsController::sendDefault($this->user->phone,124102);                
        }

    }
    public function toApp(object $notifiable)
    {
       
        if ($this->statusNotification->app) {
            $type= json_encode($this->statusNotification->type);
            ModelNotification::create([
                'user_id'=> $this->user->id,
                'title'=> $this->statusNotification->title,
                'description'=> $this->statusNotification->description,
                'type'=> $type,
    
    
    
            ]);
        }
     
    }
}
