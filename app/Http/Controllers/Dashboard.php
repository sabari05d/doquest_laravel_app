<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Controller
{
    // 
    public function dashboardIndex(Request $request)
    {
        $user = Auth::user();
        $data = [];
        $data['user'] = $user;

        // Unfinished and today's tasks (exclude completed)
        $unfinishedAndTodayTasks = $user->tasks()
            ->whereNotNull('task_date')
            ->where('status', '!=', 1) // ignore finished
            ->where(function ($query) {
                $query->whereDate('task_date', now()->toDateString()) // today's tasks
                    ->orWhereDate('task_date', '<', now()->toDateString()); // unfinished past tasks
            })
            ->orderBy('task_date', 'asc')
            ->orderBy('task_time', 'asc')
            ->get()
            ->filter()
            ->groupBy('task_date');

        // Upcoming tasks (next 7 days only, exclude completed)
        $upcomingTasks = $user->tasks()
            ->whereNotNull('task_date')
            ->where('status', '!=', 1) // ignore finished
            ->whereBetween('task_date', [now()->toDateString(), now()->addDays(7)->toDateString()]) // only next 7 days
            ->orderBy('task_date', 'asc')
            ->orderBy('task_time', 'asc')
            ->get()
            ->filter()
            ->groupBy('task_date');


        // Render both task sections separately using the same Blade partial
        $unfinishedHtml = view('tasks.partials.dashboard_list', [
            'tasks' => $unfinishedAndTodayTasks,
            'sectionTitle' => 'Today & Unfinished Tasks'
        ])->render();

        $upcomingHtml = view('tasks.partials.dashboard_list', [
            'tasks' => $upcomingTasks,
            'sectionTitle' => 'Upcoming Tasks'
        ])->render();

        $data['unfinishedHtml'] = $unfinishedHtml;
        $data['upcomingHtml'] = $upcomingHtml;

        return view('dashboard.dashboard', $data);
    }
}
