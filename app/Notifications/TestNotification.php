<?php

// php artisan make:notification TestNotification
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;

use NotificationChannels\Fcm\Resources\Notification as FcmNotification;
class TestNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return [FcmChannel::class];
    }


    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            ->notification(FcmNotification::create('Test Notification', 'This is a test notification!'))
            ->data(['type' => 'test']);
    }
}

