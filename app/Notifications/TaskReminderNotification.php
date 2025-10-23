<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
// use NotificationChannels\Fcm\Resources\Notification as FcmNotification;
// use NotificationChannels\Fcm\Resources\AndroidConfig;

use Illuminate\Contracts\Queue\ShouldQueue;

class TaskReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    // Channels
    public function via($notifiable)
    {
        return [FcmChannel::class, 'database'];
    }

    // Optional: store in database
    public function toDatabase($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'reminder_datetime' => $this->task->reminder_datetime,
        ];
    }

    // FCM message
    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            ->setData([
                'task_id' => (string) $this->task->id,
                'type' => 'task_reminder',
            ])
            ->setNotification('Task Reminder', $this->task->title)
            ->setAndroid(
                [
                    'priority' => 'high', // equivalent of PRIORITY_HIGH
                ]
            );
    }

}
