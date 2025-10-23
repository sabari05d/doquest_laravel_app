<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'task_date',
        'task_time',
        'reminder_datetime',
        'status',
        'user_id',
    ];

    // Each Task belongs to one user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
