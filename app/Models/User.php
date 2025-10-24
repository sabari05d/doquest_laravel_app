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
}
