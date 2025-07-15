<?php

use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ProfileController;
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
});

require __DIR__ . '/auth.php';
