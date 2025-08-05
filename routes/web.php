<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ActivityScheduleController;
use App\Http\Controllers\ApplicationSettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RSCDataController;
use App\Http\Controllers\SensorThresholdController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile (Pengaturan Akun)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Index Jadwal Kegiatan Praktikum
    Route::get('/activity-schedule', [ActivityScheduleController::class, 'index'])->name('activity-schedule.index');

    // Index Pengaturan Threshold
    Route::get('/rsc-data/sensor-threshold', [SensorThresholdController::class, 'index'])->name('rsc-data.sensor-threshold.index');

    // Index Log Aktivitas
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');

    // Data RSC - Raw & Filtered 
    Route::prefix('/rsc-data')->name('rsc-data.')->group(function () {
        Route::get('/', [RSCDataController::class, 'index'])->name('index');
        Route::get('/monitoring', [RSCDataController::class, 'indexMonitoring'])->name('monitoring.index');
        Route::get('/filtered-monitoring', [RSCDataController::class, 'indexFilteredMonitoring'])->name('filtered-monitoring.index');
        Route::get('/schedule', [RSCDataController::class, 'indexPenjadwalan'])->name('schedule.index');
        Route::get('/schedule/show/{id}', [RSCDataController::class, 'showPenjadwalan'])->name('schedule.show');
        Route::get('/schedule/show-filtered/{id}', [RSCDataController::class, 'showFilteredPenjadwalan'])->name('filtered-schedule.show');
        Route::get('/monitoring/device-ids', [RSCDataController::class, 'getUniqueDeviceIds'])->name('monitoring.device-ids');
        Route::get('/filtered-monitoring/device-ids', [RSCDataController::class, 'getFilteredUniqueDeviceIds'])->name('filtered-monitoring.device-ids');
    });

    // Akses Fitur Khusus Dosen & Superuser
    Route::middleware('role:superuser,dosen')->group(function () {
        // Index Master Data
        Route::get('/master-data', function () {
            return view('pages.master-data.index');
        })->name('master-data.index');

        // CRUD Data Mahasiswa
        Route::resource('students', StudentController::class)->names([
            'index' => 'student.index',
            'create' => 'student.create',
            'store' => 'student.store',
            'show' => 'student.show',
            'edit' => 'student.edit',
            'update' => 'student.update',
            'destroy' => 'student.destroy',
        ]);

        // Create, Update, Delete Jadwal Kegiatan Praktikum
        Route::resource('activity-schedule', ActivityScheduleController::class)->except('index', 'show')->names([
            'create' => 'activity-schedule.create',
            'store' => 'activity-schedule.store',
            'edit' => 'activity-schedule.edit',
            'update' => 'activity-schedule.update',
            'destroy' => 'activity-schedule.destroy',
        ]);

        // Create, Update, Delete Threshold
        Route::resource('/rsc-data/sensor-threshold', SensorThresholdController::class)->except('index', 'show')->names([
            'create' => 'rsc-data.sensor-threshold.create',
            'store' => 'rsc-data.sensor-threshold.store',
            'edit' => 'rsc-data.sensor-threshold.edit',
            'update' => 'rsc-data.sensor-threshold.update',
            'destroy' => 'rsc-data.sensor-threshold.destroy',
        ]);

        // Index & Save Pengaturan Aplikasi
        Route::get('/application-setting', [ApplicationSettingController::class, 'index'])->name('application-setting.index');
        Route::post('/application-setting', [ApplicationSettingController::class, 'save'])->name('application-setting.save');
    });

    // Akses Fitur Khusus Superuser
    Route::middleware('role:superuser')->group(function () {
        // CRUD Data Dosen
        Route::resource('lecturers', LecturerController::class)->names([
            'index' => 'lecturer.index',
            'create' => 'lecturer.create',
            'store' => 'lecturer.store',
            'show' => 'lecturer.show',
            'edit' => 'lecturer.edit',
            'update' => 'lecturer.update',
            'destroy' => 'lecturer.destroy',
        ]);

        // CRUD Data Perangkat
        Route::resource('device', DeviceController::class)->names([
            'index' => 'device.index',
            'create' => 'device.create',
            'store' => 'device.store',
            'show' => 'device.show',
            'edit' => 'device.edit',
            'update' => 'device.update',
            'destroy' => 'device.destroy',
        ]);
    });
});

require __DIR__ . '/auth.php';
