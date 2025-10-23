<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Tasks extends Controller
{
    //

    public function taskIndex()
    {
        $user = Auth::user();
        $tasks = $user->tasks()
            ->whereNotNull('task_date')
            ->orderBy('task_date', 'asc')
            ->orderBy('task_time', 'asc')
            ->get()
            ->filter()
            ->groupBy('task_date');
        return view('tasks.task', [
            'user' => $user,
            'tasks' => $tasks
        ]);
    }

    public function openTaskModal($id = 0)
    {
        if ($id == 0) {
            return view('tasks.add');
        } else {
            $task = Task::find($id);
            if ($task->user_id != Auth::id()) {
                return redirect()->back()->with('error', 'Unauthorized action.');
            }
            return view('tasks.edit', compact('id', 'task'));
        }
    }

    public function saveTask(Request $request, $id = 0)
    {
        // Validation rules
        $request->validate([
            'title' => 'required|string|max:255',
            'task_date' => 'required|date|after_or_equal:today', // cannot be past
            'task_time' => 'nullable', // optional
            'reminder_datetime' => 'nullable|date|after_or_equal:now', // optional but must be future if set
        ]);

        // If adding new task
        if ($id == 0) {
            $task = new Task();
            $task->user_id = Auth::id();
        }
        // Else updating existing task
        else {
            $task = Task::find($id);

            if (!$task) {
                return response()->json(['error' => 'Task not found.'], 404);
            }

            if ($task->user_id != Auth::id()) {
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }
        }

        // Common fields
        $task->title = $request->title;
        $task->task_date = $request->task_date;
        $task->task_time = $request->task_time;
        $task->reminder_datetime = $request->reminder_datetime;
        $task->status = $task->status ?? 0;

        $task->save();

        // Response for AJAX or normal form
        if ($request->ajax()) {
            return response()->json([
                'message' => $id == 0 ? 'Task added successfully!' : 'Task updated successfully!',
                'task' => $task
            ]);
        }

        return redirect()->back()->with('success', $id == 0 ? 'Task added successfully!' : 'Task updated successfully!');
    }

    public function fetchTasks()
    {
        $user = Auth::user(); // get the user object
        $tasks = $user->tasks()
            ->whereNotNull('task_date')
            ->orderBy('task_date', 'asc')
            ->orderBy('task_time', 'asc')
            ->get()
            ->filter()
            ->groupBy('task_date');

        // Render a partial Blade view for tasks (just the cards)
        $tasksHtml = view('tasks.partials.tasks_list', compact('tasks'))->render();

        return response()->json([
            'tasks_html' => $tasksHtml
        ]);
    }

    public function updateTaskStatus(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        if ($task->user_id != Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Update status (1 = finished, 0 = pending)
        $task->status = $request->status == 1 ? 1 : 0;
        $task->save();

        return response()->json([
            'message' => 'Task status updated successfully',
            'status' => $task->status
        ]);
    }

    public function deleteTask($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        if ($task->user_id != Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully'
        ]);
    }

}
