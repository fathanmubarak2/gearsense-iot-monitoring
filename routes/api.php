<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelemetryController;

/*
|--------------------------------------------------------------------------
| Gearsense IoT & Telemetry API Routes
|--------------------------------------------------------------------------
| Endpoint komunikasi untuk pengiriman data telemetri dari mikrokontroler ESP32
| dan sensor ADXL345 ke backend web Gearsense sesuai spesifikasi Bab IV.
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Endpoint penerimaan data telemetri getaran dari node ESP32 (HTTP POST JSON)
Route::post('/telemetry', [TelemetryController::class, 'store'])
    ->withoutMiddleware('throttle:api')
    ->name('api.telemetry.store');

// Alias v1 untuk kompabilitas firmware mikrokontroler
Route::post('/v1/telemetry', [TelemetryController::class, 'store'])
    ->withoutMiddleware('throttle:api');

// Endpoint untuk polling telemetri terkini oleh dashboard real-time
Route::get('/telemetry/latest', [TelemetryController::class, 'latest'])
    ->withoutMiddleware('throttle:api')
    ->name('api.telemetry.latest');

use App\Http\Controllers\DeviceController;

// Endpoint instruksi dua arah untuk dibaca oleh ESP32 (kontrol motor & status)
Route::get('/device/command', [DeviceController::class, 'getCommand'])->name('api.device.command');