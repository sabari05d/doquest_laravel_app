<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Notifications\TaskReminderNotification;
use Carbon\Carbon;

class SendTaskReminders extends Command
{
    protected $signature = 'tasks:send-reminders';
    protected $description = 'Send notifications for tasks whose reminder time has arrived.';

    public function handle()
    {
        $now = Carbon::now()->second(0);

        $tasks = Task::whereNotNull('reminder_datetime')
            ->where('status', '!=', 1) // not completed
            ->where('reminded', 0)
            ->whereBetween('reminder_datetime', [now()->subMinutes(2), now()->addMinute()])
            ->with('user')
            ->get();

        foreach ($tasks as $task) {
            if ($task->user) {
                $task->user->notify(new TaskReminderNotification($task));
                $this->info("Reminder sent for Task ID: {$task->id}");
            }
        }

        return 0;
    }
}
