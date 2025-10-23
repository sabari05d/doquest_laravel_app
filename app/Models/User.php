<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\Fcm\FcmChannel;

class User extends Authenticatable
{
    use Notifiable;
    protected $guarded = [];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }


    public function routeNotificationForFcm()
    {
        return $this->deviceTokens()->pluck('token')->toArray();
    }

    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }
}
