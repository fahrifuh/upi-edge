<?php

use App\Http\Controllers\RSCDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/rsc-data', [RSCDataController::class, 'handleSensorData']);
