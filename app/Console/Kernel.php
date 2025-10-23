<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Task;
use App\Notifications\TaskReminderNotification;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $now = now();

            $tasks = Task::where('status', 0)
                ->where('reminded', 0)
                ->whereNotNull('reminder_datetime')
                ->whereBetween('reminder_datetime', [now()->subMinutes(2), now()->addMinute()])
                ->with('user')
                ->get();

            foreach ($tasks as $task) {
                if ($task->user) {
                    $task->user->notify(new TaskReminderNotification($task));
                    $task->update(['reminded' => 1]);
                }

            }
        })->everyMinute();
    }

    protected function commands()
    {
        // load artisan commands if needed
    }
}
