<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SecurityAuditLog;

class AuditLogController extends Controller
{
    /**
     * Tampilkan Halaman Log Keamanan & Aktivitas
     */
    public function index()
    {
        try {
            $logs = SecurityAuditLog::orderBy('id', 'desc')->take(100)->get();
            $totalLogs = SecurityAuditLog::count();
            $dangerCount = SecurityAuditLog::where('severity', 'DANGER')->count();
            $warningCount = SecurityAuditLog::where('severity', 'WARNING')->count();
            $infoCount = SecurityAuditLog::where('severity', 'INFO')->count();
        } catch (\Throwable $e) {
            $logs = collect([]);
            $totalLogs = 0; $dangerCount = 0; $warningCount = 0; $infoCount = 0;
        }

        return view('log-keamanan', compact('logs', 'totalLogs', 'dangerCount', 'warningCount', 'infoCount'));
    }

    /**
     * Bersihkan Log Keamanan Lama (POST /audit-logs/clear)
     */
    public function clear(Request $request)
    {
        SecurityAuditLog::truncate();

        SecurityAuditLog::create([
            'user_id'     => session('gearsense_user_id'),
            'username'    => session('gearsense_username', 'admin'),
            'event_type'  => 'LOGS_CLEARED',
            'severity'    => 'INFO',
            'description' => 'Log aktivitas keamanan sistem telah dibersihkan oleh Administrator.',
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Seluruh riwayat log keamanan berhasil dibersihkan.',
        ]);
    }
}
