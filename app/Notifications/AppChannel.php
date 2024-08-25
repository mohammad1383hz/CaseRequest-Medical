<?php
 
namespace App\Notifications;
 
use Illuminate\Notifications\Notification;
 
class AppChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $message = $notification->toApp($notifiable);
 
        // Send notification to the $notifiable instance...
    }
}