<?php

namespace App\Notifications;

use App\Http\Controllers\V1\Notification\SmsController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Notification as ModelNotification;
class GeneralNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $request;


    public function __construct($user,$request)
    {
        $this->user = $user;
        $this->request = $request;
        

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
   
    public function via($notifiable)
    {
        // return ['mail'];

        if (in_array("mail", $this->request->type)) {
        return [AppChannel::class,SmsChannel::class,FcmChannel::class,'mail'];

        }
        return [AppChannel::class,SmsChannel::class,FcmChannel::class];

      

    }
 
    /**
     * Get the voice representation of the notification.
     */
    public function toSms(object $notifiable)
    {
        return 'sms';

    }
    public function toFcm(object $notifiable)
    {
        return 'eree';
    }
    public function toApp(object $notifiable)
    {
       
        if (in_array("app", $this->request->type)) {
           $type= json_encode($this->request->type);
            ModelNotification::create([
                'user_id'=> $this->user->id,
                'title'=> $this->request->title,
                'description'=> $this->request->description,
                'type'=> $type,
    
    
    
            ]);
        }
     
    }
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        if (in_array("mail", $this->request->type)) {
        return (new MailMessage)->view(
            'emails.general', ['title'=> $this->request->title,
            'description'=> $this->request->description]
        );
    }
    }
    

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
        ];
    }
}
