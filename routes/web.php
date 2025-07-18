<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ActivityScheduleController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RSCDataController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('lecturers', LecturerController::class)->names([
        'index' => 'lecturer.index',
        'create' => 'lecturer.create',
        'store' => 'lecturer.store',
        'show' => 'lecturer.show',
        'edit' => 'lecturer.edit',
        'update' => 'lecturer.update',
        'destroy' => 'lecturer.destroy',
    ]);

    Route::resource('students', StudentController::class)->names([
        'index' => 'student.index',
        'create' => 'student.create',
        'store' => 'student.store',
        'show' => 'student.show',
        'edit' => 'student.edit',
        'update' => 'student.update',
        'destroy' => 'student.destroy',
    ]);

    Route::resource('activity-schedule', ActivityScheduleController::class)->except('show')->names([
        'index' => 'activity-schedule.index',
        'create' => 'activity-schedule.create',
        'store' => 'activity-schedule.store',
        'edit' => 'activity-schedule.edit',
        'update' => 'activity-schedule.update',
        'destroy' => 'activity-schedule.destroy',
    ]);

    Route::resource('device', DeviceController::class)->names([
        'index' => 'device.index',
        'create' => 'device.create',
        'store' => 'device.store',
        'show' => 'device.show',
        'edit' => 'device.edit',
        'update' => 'device.update',
        'destroy' => 'device.destroy',
    ]);

    Route::get('activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::prefix('/rsc-data')->name('rsc-data.')->group(function () {
        Route::get('/', [RSCDataController::class, 'index'])->name('index');
        Route::get('/monitoring', [RSCDataController::class, 'indexMonitoring'])->name('monitoring.index');
        Route::get('/schedule', [RSCDataController::class, 'indexPenjadwalan'])->name('schedule.index');
        Route::get('/schedule/show/{id}', [RSCDataController::class, 'showPenjadwalan'])->name('schedule.show');
    });
});

require __DIR__ . '/auth.php';
