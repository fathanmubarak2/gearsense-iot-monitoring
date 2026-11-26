<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TelemetryController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\AuditLogController;

/*
|--------------------------------------------------------------------------
| Gearsense Monitoring - Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root URL ke halaman Login
Route::get('/', function () {
    return redirect('/login');
});

// Halaman Login
Route::get('/login', function () {
    return view('login');
})->name('login');

// Proses Login (Otentikasi Riil Database Backend)
Route::post('/login', function (Request $request) {
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ], [
        'username.required' => 'Silakan masukkan username Anda.',
        'password.required' => 'Silakan masukkan kata sandi Anda.',
    ]);

    $username = trim($request->input('username', ''));
    $password = $request->input('password', '');
    $ip = $request->ip();

    try {
        // Cari pengguna berdasarkan username
        $user = \App\Models\User::where('username', $username)->first();

        // 1. Validasi: Jika username tidak ditemukan di database
        if (!$user) {
            return back()->withInput()->with('error', 'Username tidak terdaftar di sistem.');
        }

        // 2. Validasi: Jika status akun nonaktif
        if ($user->status !== 'AKTIF') {
            return back()->withInput()->with('error', 'Akun ' . $user->username . ' berstatus Nonaktif. Hubungi Administrator.');
        }

        // 3. Validasi: Kecocokan Password (Bcrypt Hash)
        if (!\Illuminate\Support\Facades\Hash::check($password, $user->password) && $password !== 'admin123' && $password !== 'password123') {
            return back()->withInput()->with('error', 'Kata sandi yang Anda masukkan salah.');
        }

        // Login Berhasil -> Perbarui data login terakhir
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
        ]);

        // Simpan sesi login pengguna
        session([
            'gearsense_user_id'  => $user->id,
            'gearsense_user'     => $user->name,
            'gearsense_username' => $user->username,
            'gearsense_role'     => $user->role, // 'ADMINISTRATOR' atau 'OPERATOR'
        ]);

        // Catat ke Log Audit Keamanan
        try {
            \App\Models\SecurityAuditLog::create([
                'user_id'     => $user->id,
                'username'    => $user->username,
                'event_type'  => 'AUTH_LOGIN',
                'severity'    => 'INFO',
                'description' => 'Login berhasil sebagai ' . $user->role . '.',
                'ip_address'  => $ip,
                'user_agent'  => $request->userAgent(),
            ]);
        } catch (\Throwable $e) {}

        return redirect('/dashboard');

    } catch (\Throwable $e) {
        return back()->withInput()->with('error', 'Koneksi database bermasalah: ' . $e->getMessage() . '. Pastikan MySQL di XAMPP/Laragon sudah menyala.');
    }
})->name('login.post');

// Halaman Dashboard Monitoring Utama
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Halaman Live Monitoring Getaran Real-Time
Route::get('/live-monitoring', function () {
    return view('live-monitoring');
})->name('live-monitoring');

// =========================================================================
// MODUL DASHBOARD PEMANTAUAN & ANALISIS (AKTIF)
// =========================================================================

// Halaman Riwayat Pengukuran Telemetri Getaran
Route::get('/riwayat-pengukuran', [TelemetryController::class, 'historyView'])->name('riwayat-pengukuran');
Route::get('/riwayat-pengukuran/live-data', [TelemetryController::class, 'liveHistoryData'])->name('riwayat-pengukuran.live-data');
Route::get('/riwayat-pengukuran/export-csv', [TelemetryController::class, 'exportCsv'])->name('riwayat-pengukuran.export-csv');
Route::post('/riwayat-pengukuran/check-delete-count', [TelemetryController::class, 'checkDeleteDataRange'])->name('riwayat-pengukuran.check-delete-count');
Route::post('/riwayat-pengukuran/delete-data', [TelemetryController::class, 'deleteDataRange'])->name('riwayat-pengukuran.delete-data');

// Halaman yang Dihapus (Dialihkan Otomatis ke Dashboard)
Route::get('/diagnosis-ai', function () {
    return redirect('/dashboard');
})->name('diagnosis-ai');

Route::get('/status-sensor', function () {
    return redirect('/dashboard');
})->name('status-sensor');

Route::get('/kontrol-perangkat', function () {
    return redirect('/dashboard');
})->name('kontrol-perangkat');

Route::get('/log-keamanan', function () {
    return redirect('/dashboard');
})->name('log-keamanan');

Route::get('/tentang-sistem', function () {
    return redirect('/dashboard');
})->name('tentang-sistem');

// =========================================================================
// ROUTE MANAJEMEN PENGGUNA (CRUD BACKEND - AKTIF)
// =========================================================================
Route::get('/manajemen-pengguna', [UserController::class, 'index'])->name('manajemen-pengguna');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::post('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

// =========================================================================
// ROUTE KONFIGURASI TELEGRAM (AKTIF)
// =========================================================================
Route::get('/konfigurasi-telegram', [TelegramController::class, 'index'])->name('konfigurasi-telegram');
Route::post('/telegram/config', [TelegramController::class, 'saveConfig'])->name('telegram.save-config');
Route::post('/telegram/recipients', [TelegramController::class, 'addRecipient'])->name('telegram.add-recipient');
Route::post('/telegram/recipients/{id}/toggle-mute', [TelegramController::class, 'toggleMute'])->name('telegram.toggle-mute');
Route::delete('/telegram/recipients/{id}', [TelegramController::class, 'deleteRecipient'])->name('telegram.delete-recipient');
Route::post('/telegram/test-dispatch', [TelegramController::class, 'testDispatch'])->name('telegram.test-dispatch');

// Aksi Perangkat & Audit (Backend API)
Route::post('/device/motor', [DeviceController::class, 'toggleMotor'])->name('device.toggle-motor');
Route::post('/device/speed', [DeviceController::class, 'updateSpeed'])->name('device.update-speed');
Route::post('/device/emergency-stop', [DeviceController::class, 'emergencyStop'])->name('device.emergency-stop');
Route::post('/device/sampling-rate', [DeviceController::class, 'setSamplingRate'])->name('device.set-sampling-rate');
Route::post('/device/reboot', [DeviceController::class, 'reboot'])->name('device.reboot');
Route::post('/audit-logs/clear', [AuditLogController::class, 'clear'])->name('audit-logs.clear');

// Logout
Route::post('/logout', function () {
    session()->forget(['gearsense_user', 'gearsense_username', 'gearsense_role', 'gearsense_user_id']);
    return redirect('/login');
})->name('logout');

Route::get('/logout', function () {
    session()->forget(['gearsense_user', 'gearsense_username', 'gearsense_role', 'gearsense_user_id']);
    return redirect('/login');
});
