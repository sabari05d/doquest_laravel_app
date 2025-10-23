<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckListController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\DeviceTokenController;
use App\Http\Controllers\Profile;
use App\Http\Controllers\Tasks;
use App\Models\Task;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('landing');
    })->name('landing');

    // Views
    Route::get('/onboard', [AuthController::class, 'onboardIndex'])->name('onboard');
    Route::get('/register', [AuthController::class, 'registerIndex'])->name('signUp');
    Route::get('/login', [AuthController::class, 'loginIndex'])->name('login');

    // Posts
    Route::post('/register', [AuthController::class, 'register'])->name('registerUser');
    Route::post('/check-unique', [AuthController::class, 'checkUserIsUnique'])->name('check.unique');
    Route::post('/check-user', [AuthController::class, 'checkUser'])->name('checkUser');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {

    Route::get('/test-notification', function () {
        $user = \App\Models\User::first();
        $user->notify(new \App\Notifications\TestNotification());
        return 'Notification sent!';
    });

    // Dashboard
    Route::get('/dashboard', [Dashboard::class, 'dashboardIndex'])->name('dashboard');

    // Profile
    Route::get('/profile', [Profile::class, 'profileIndex'])->name('profile');
    Route::post('/update-profile', [Profile::class, 'updateProfile'])->name('updateProfile');

    Route::get('/settings', [Dashboard::class, 'settings'])->name('settings');

    // Tasks
    Route::get('/tasks', [Tasks::class, 'taskIndex'])->name('tasks');
    Route::get('/open-task/{id?}', [Tasks::class, 'openTaskModal'])->name('openTaskModal');
    Route::post('/save-task/{id?}', [Tasks::class, 'saveTask'])->name('saveTask');
    Route::get('/fetch-tasks', [Tasks::class, 'fetchTasks'])->name('allTasks');
    Route::post('/delete-task/{id}', [Tasks::class, 'deleteTask'])->name('deleteTask');
    Route::post('/update-task/{id}', [Tasks::class, 'updateTaskStatus'])->name('updatStatus');


    Route::get('/checklist', [CheckListController::class, 'index'])->name('checklist.index');

    Route::post('/checklist/add-group', [CheckListController::class, 'addGroup'])->name('checklist.addGroup');
    Route::delete('/checklist/delete-group/{id}', [CheckListController::class, 'deleteGroup'])->name('checklist.deleteGroup');

    Route::post('/checklist/{groupId}/add-item', [CheckListController::class, 'addItem'])->name('checklist.addItem');
    Route::patch('/checklist/toggle-item/{itemId}', [CheckListController::class, 'toggleItem'])->name('checklist.toggleItem');
    Route::delete('/checklist/delete-item/{itemId}', [CheckListController::class, 'deleteItem'])->name('checklist.deleteItem');

    Route::delete('/checklist/{groupId}/clear-items', [CheckListController::class, 'clearItems'])->name('checklist.clearItems');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


    Route::post('/device-token', [DeviceTokenController::class, 'store']);


    Route::get('/check-reminders', function () {
        $user = Auth::user();
        $reminders = Task::where('user_id', $user->id)
            ->where('status', 0)
            ->whereBetween('reminder_datetime', [now()->subMinute(), now()])
            ->get(['id', 'title']);
        return response()->json(['reminders' => $reminders]);
    });


});


