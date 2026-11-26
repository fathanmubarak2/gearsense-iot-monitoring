<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VibrationTelemetry;
use App\Models\DeviceNode;
use App\Models\IncidentLog;
use App\Models\TelegramConfig;
use App\Models\TelegramRecipient;
use App\Models\SecurityAuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TelemetryController extends Controller
{
    /**
     * Endpoint API Penerimaan Telemetri dari ESP32 (POST /api/telemetry)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'node_code'         => 'nullable|string|max:50',
            'node_id'           => 'nullable|string|max:50', // alias
            'raw_x'             => 'nullable|numeric',
            'raw_y'             => 'nullable|numeric',
            'raw_z'             => 'nullable|numeric',
            'p2p_x'             => 'nullable|numeric',
            'p2p_y'             => 'nullable|numeric',
            'p2p_z'             => 'nullable|numeric',
            'wamp_z'            => 'nullable|numeric',
            'wamp_y'            => 'nullable|numeric',
            'rms_z'             => 'nullable|numeric',
            'kurtosis_z'        => 'nullable|numeric',
            'skewness_z'        => 'nullable|numeric',
            'condition'         => 'nullable|string|max:30', // NORMAL, SCORING, DAMAGED
            'confidence_score'  => 'nullable|numeric',
            'fis_score'         => 'nullable|numeric',
            'sampling_rate_hz'  => 'nullable|integer',
            'recorded_at'       => 'nullable|date',
            'motor_pwm'         => 'nullable|integer',
            'motor_running'     => 'nullable|boolean',
        ]);

        $nodeCode = $validated['node_code'] ?? $validated['node_id'] ?? 'ESP32-GEARSENSE-01';

        // Normalisasi status kondisi
        $condRaw = strtoupper($validated['condition'] ?? 'NORMAL');
        if (strpos($condRaw, 'TERKIKIS') !== false || strpos($condRaw, 'SCOR') !== false) {
            $condition = 'SCORING';
        } elseif (strpos($condRaw, 'RUSAK') !== false || strpos($condRaw, 'DAMAG') !== false) {
            $condition = 'DAMAGED';
        } else {
            $condition = 'NORMAL';
        }

        $recordedAt = isset($validated['recorded_at']) ? Carbon::parse($validated['recorded_at'])->setTimezone('Asia/Jakarta') : Carbon::now('Asia/Jakarta');

        // 1. Simpan ke database vibration_telemetries
        $telemetry = VibrationTelemetry::create([
            'node_code'         => $nodeCode,
            'raw_x'             => $validated['raw_x'] ?? 0.05,
            'raw_y'             => $validated['raw_y'] ?? -0.02,
            'raw_z'             => $validated['raw_z'] ?? 0.98,
            'p2p_x'             => $validated['p2p_x'] ?? 0.12,
            'p2p_y'             => $validated['p2p_y'] ?? 0.18,
            'p2p_z'             => $validated['p2p_z'] ?? 0.35,
            'wamp_z'            => $validated['wamp_z'] ?? 0.08,
            'wamp_y'            => $validated['wamp_y'] ?? 0.06,
            'rms_z'             => $validated['rms_z'] ?? 0.22,
            'kurtosis_z'        => $validated['kurtosis_z'] ?? 2.85,
            'skewness_z'        => $validated['skewness_z'] ?? 0.15,
            'condition'         => $condition,
            'confidence_score'  => $validated['confidence_score'] ?? 92.5,
            'fis_score'         => $validated['fis_score'] ?? 18.4,
            'sampling_rate_hz'  => 50, // Standar Resmi Dokumen TA: 50 Hz
            'recorded_at'       => $recordedAt,
        ]);

        // 2. Perbarui Device Node heartbeat
        try {
            $device = DeviceNode::firstOrNew(['node_code' => $nodeCode]);
            $device->connection_status = 'ONLINE';
            $device->ip_address = $request->ip() ?: ($device->ip_address ?? '192.168.1.105');
            $device->last_heartbeat_at = Carbon::now();
            $device->sampling_rate_hz = 50; // Standar 50 Hz
            if (isset($validated['motor_pwm'])) {
                $device->motor_speed_pwm = $validated['motor_pwm'];
            }
            if (isset($validated['motor_running'])) {
                $device->motor_state = $validated['motor_running'] ? 'RUNNING' : 'STOPPED';
            }
            $device->save();
        } catch (\Throwable $e) {}

        // 3. Tangani Anomali & Insiden jika terjadi Scoring / Damaged (Debounced agar tidak blocking)
        if (in_array($condition, ['SCORING', 'DAMAGED'])) {
            $lastIncidentKey = 'last_incident_' . $nodeCode . '_' . $condition;
            $lastLogged = Cache::get($lastIncidentKey);
            if (!$lastLogged || (now()->timestamp - $lastLogged > 30)) {
                Cache::put($lastIncidentKey, now()->timestamp, 60);
                $this->handleIncidentDetection($nodeCode, $condition, $telemetry);
            }
        }

        // 4. Update Cache kilat (In-Memory Microsecond RAM) untuk polling instan Dashboard
        $nowTimestamp = microtime(true);
        $clientIp = $request->ip() ?: ($device->ip_address ?? '192.168.100.8');
        $latestPayload = [
            'timestamp'         => $recordedAt->toIso8601String(),
            'formatted_time'    => $recordedAt->format('H:i:s'),
            'node_id'           => $nodeCode,
            'node_code'         => $nodeCode,
            'ip_address'        => $clientIp,
            'condition'         => $condition,
            'p2p_z'             => (float)($telemetry->p2p_z ?? 0),
            'p2p_x'             => (float)($telemetry->p2p_x ?? 0),
            'p2p_y'             => (float)($telemetry->p2p_y ?? 0),
            'wamp_z'            => (float)($telemetry->wamp_z ?? 0),
            'wamp_y'            => (float)($telemetry->wamp_y ?? 0),
            'raw_z'             => (float)($telemetry->raw_z ?? 0),
            'raw_y'             => (float)($telemetry->raw_y ?? 0),
            'raw_x'             => (float)($telemetry->raw_x ?? 0),
            'rms_z'             => (float)($telemetry->rms_z ?? 0),
            'kurtosis_z'        => (float)($telemetry->kurtosis_z ?? 0),
            'skewness_z'        => (float)($telemetry->skewness_z ?? 0),
            'fis_score'         => (float)($telemetry->fis_score ?? 0),
            'confidence_score'  => (float)($telemetry->confidence_score ?? 0),
            'sampling_rate_hz'  => 50,
            'status'            => 'ONLINE',
            'is_online'         => true,
            'cached_at'         => $nowTimestamp,
        ];
        Cache::put('latest_gearsense_telemetry', $latestPayload, now()->addMinutes(10));

        return response()->json([
            'status'         => 'success',
            'message'        => 'Data telemetri berhasil disimpan ke database Gearsense',
            'device_command' => [
                'motor_state'     => $device->motor_state ?? 'STOPPED',
                'motor_speed_pwm' => $device->motor_speed_pwm ?? 0,
                'emergency_stop'  => ($device->motor_state ?? '') === 'EMERGENCY_STOP',
            ],
            'data'           => $latestPayload,
        ], 201);
    }

    /**
     * Endpoint Polling Telemetri Terkini (GET /api/telemetry/latest)
     * Respon super kilat via In-Memory Cache RAM (<1ms) untuk mencegah bottleneck antrean server
     */
    public function latest()
    {
        $cached = Cache::get('latest_gearsense_telemetry');

        if ($cached && isset($cached['cached_at'])) {
            $diffSeconds = microtime(true) - (float)$cached['cached_at'];
            $isOnline = ($diffSeconds <= 2.5);

            $cached['diff_seconds'] = round($diffSeconds, 2);
            $cached['is_online']    = $isOnline;
            $cached['status']       = $isOnline ? 'ONLINE' : 'OFFLINE';
            if (!$isOnline) {
                $cached['condition'] = 'OFFLINE';
            }

            return response()->json($cached);
        }

        // Fallback jika cache kosong: Baca dari database
        $latestRow = VibrationTelemetry::orderBy('id', 'desc')->first();
        $diffSeconds = 999;
        $isOnline = false;

        if ($latestRow && $latestRow->recorded_at) {
            $diffSeconds = Carbon::now()->diffInSeconds($latestRow->recorded_at);
            $isOnline = ($diffSeconds <= 3);
        }

        $device = $latestRow ? DeviceNode::where('node_code', $latestRow->node_code)->first() : DeviceNode::first();
        $nodeIp = $device ? ($device->ip_address ?? '192.168.100.8') : '192.168.100.8';

        if ($latestRow) {
            return response()->json([
                'timestamp'         => $latestRow->recorded_at ? $latestRow->recorded_at->toIso8601String() : now()->toIso8601String(),
                'formatted_time'    => $latestRow->recorded_at ? $latestRow->recorded_at->format('H:i:s') : now()->format('H:i:s'),
                'node_id'           => $latestRow->node_code,
                'node_code'         => $latestRow->node_code,
                'ip_address'        => $nodeIp,
                'condition'         => $isOnline ? $latestRow->condition : 'OFFLINE',
                'p2p_z'             => $isOnline ? (float)$latestRow->p2p_z : 0.0,
                'p2p_x'             => $isOnline ? (float)$latestRow->p2p_x : 0.0,
                'p2p_y'             => $isOnline ? (float)$latestRow->p2p_y : 0.0,
                'wamp_z'            => $isOnline ? (float)$latestRow->wamp_z : 0.0,
                'wamp_y'            => $isOnline ? (float)$latestRow->wamp_y : 0.0,
                'raw_z'             => $isOnline ? (float)$latestRow->raw_z : 0.0,
                'raw_y'             => $isOnline ? (float)$latestRow->raw_y : 0.0,
                'raw_x'             => $isOnline ? (float)$latestRow->raw_x : 0.0,
                'rms_z'             => $isOnline ? (float)$latestRow->rms_z : 0.0,
                'kurtosis_z'        => $isOnline ? (float)$latestRow->kurtosis_z : 0.0,
                'fis_score'         => $isOnline ? (float)$latestRow->fis_score : 0.0,
                'confidence_score'  => $isOnline ? (float)$latestRow->confidence_score : 0.0,
                'sampling_rate_hz'  => 50,
                'status'            => $isOnline ? 'ONLINE' : 'OFFLINE',
                'is_online'         => $isOnline,
                'diff_seconds'      => $diffSeconds,
            ]);
        }

        return response()->json([
            'status'       => 'OFFLINE',
            'is_online'    => false,
            'node_code'    => 'ESP32-GEARSENSE-01',
            'ip_address'   => $nodeIp,
            'condition'    => 'STANDBY',
            'raw_z'        => 0.0,
            'raw_x'        => 0.0,
            'raw_y'        => 0.0,
            'diff_seconds' => 999,
        ]);
    }

    /**
     * Halaman Riwayat Pengukuran (GET /riwayat-pengukuran)
     */
    public function historyView(Request $request)
    {
        try {
            $range = $request->query('range', 'all');
            $query = VibrationTelemetry::orderBy('id', 'desc');

            if ($range === 'today') {
                $query->whereDate('recorded_at', Carbon::today());
            } elseif ($range === '7d' || $range === 'week') {
                $query->where('recorded_at', '>=', Carbon::now()->subDays(7));
            } elseif ($range === '30d') {
                $query->where('recorded_at', '>=', Carbon::now()->subDays(30));
            }

            $telemetries = $query->take(100)->get();
            $totalCount = VibrationTelemetry::count();
            $normalCount = VibrationTelemetry::where('condition', 'NORMAL')->count();
            $scoringCount = VibrationTelemetry::where('condition', 'SCORING')->count();
            $damagedCount = VibrationTelemetry::where('condition', 'DAMAGED')->count();

            // Kalkulasi Tren 24 Jam Terakhir (WIB)
            $hourlyBuckets = [];
            for ($i = 23; $i >= 0; $i--) {
                $hourPoint = Carbon::now('Asia/Jakarta')->subHours($i);
                $hourlyBuckets[] = [
                    'label'   => $hourPoint->format('H:00'),
                    'utc_key' => $hourPoint->copy()->setTimezone('UTC')->format('Y-m-d H:00'),
                ];
            }

            $rawHourly = VibrationTelemetry::where('recorded_at', '>=', Carbon::now()->subHours(24))
                ->selectRaw('DATE_FORMAT(recorded_at, "%Y-%m-%d %H:00") as utc_hour, AVG(rms_z) as avg_rms')
                ->groupBy('utc_hour')
                ->get()
                ->keyBy('utc_hour');

            $chart24hLabels = [];
            $chart24hRms = [];
            foreach ($hourlyBuckets as $b) {
                $chart24hLabels[] = $b['label'];
                $val = isset($rawHourly[$b['utc_key']]) ? round((float)$rawHourly[$b['utc_key']]->avg_rms, 4) : 0.0;
                $chart24hRms[] = $val;
            }

            // Statistik RMS 24 Jam
            $maxTelemetry24h = VibrationTelemetry::where('recorded_at', '>=', Carbon::now()->subHours(24))
                ->orderBy('rms_z', 'desc')
                ->first();
            $peakRmsVal = $maxTelemetry24h ? round((float)$maxTelemetry24h->rms_z, 4) : 0.0;
            $peakRmsTime = $maxTelemetry24h && $maxTelemetry24h->recorded_at ? $maxTelemetry24h->recorded_at->copy()->setTimezone('Asia/Jakarta')->format('H:i') . ' WIB' : '-';
            $avgRms24h = round((float)(VibrationTelemetry::where('recorded_at', '>=', Carbon::now()->subHours(24))->avg('rms_z') ?? 0), 4);

            // 25 Sampel Real-time Terakhir (WIB)
            $recent25 = $telemetries->take(25)->reverse()->values();
            $chartRtLabels = $recent25->map(fn($r) => $r->recorded_at ? $r->recorded_at->copy()->setTimezone('Asia/Jakarta')->format('H:i:s') : '00:00:00')->toArray();
            $chartRtRms = $recent25->map(fn($r) => round((float)$r->rms_z, 4))->toArray();
        } catch (\Throwable $e) {
            $telemetries = collect([]);
            $totalCount = 0; $normalCount = 0; $scoringCount = 0; $damagedCount = 0;
            $chart24hLabels = []; $chart24hRms = [];
            $chartRtLabels = []; $chartRtRms = [];
            $peakRmsVal = 0; $peakRmsTime = '-'; $avgRms24h = 0;
        }

        return view('riwayat-pengukuran', compact(
            'telemetries', 'totalCount', 'normalCount', 'scoringCount', 'damagedCount',
            'chart24hLabels', 'chart24hRms', 'chartRtLabels', 'chartRtRms',
            'peakRmsVal', 'peakRmsTime', 'avgRms24h'
        ));
    }

    /**
     * Endpoint Data Real-time Polling untuk Riwayat Pengukuran (GET /riwayat-pengukuran/live-data)
     */
    public function liveHistoryData(Request $request)
    {
        try {
            $lastId = (int)$request->query('last_id', 0);
            
            // Ambil rekaman baru yang masuk setelah last_id
            $newRowsQuery = VibrationTelemetry::query();
            if ($lastId > 0) {
                $newRowsQuery->where('id', '>', $lastId);
            }
            $newRows = $newRowsQuery->orderBy('id', 'asc')->take(50)->get();

            $formattedRows = $newRows->map(function($t) {
                $timeWib = $t->recorded_at ? $t->recorded_at->copy()->setTimezone('Asia/Jakarta') : null;
                $cond = $t->condition;
                $condText = ($cond === 'DAMAGED') ? 'RUSAK' : (($cond === 'SCORING') ? 'TERKIKIS' : 'NORMAL');
                $condBg = ($cond === 'DAMAGED') ? '#fee2e2' : (($cond === 'SCORING') ? '#fef3c7' : '#ccfbf1');
                $condColor = ($cond === 'DAMAGED') ? '#dc2626' : (($cond === 'SCORING') ? '#d97706' : '#0d8a7c');
                $condBorder = ($cond === 'DAMAGED') ? '#ef4444' : (($cond === 'SCORING') ? '#f59e0b' : '#14b8a6');
                $sessionId = '#GS-' . ($timeWib ? $timeWib->format('Ymd-Hi') : '20261031-1420') . '-' . str_pad($t->id, 3, '0', STR_PAD_LEFT);
                $timeFormatted = $timeWib ? $timeWib->translatedFormat('d M Y, H:i:s') : '-';

                return [
                    'id'               => $t->id,
                    'session_id'       => $sessionId,
                    'time_formatted'   => $timeFormatted,
                    'time_clock'       => $timeWib ? $timeWib->format('H:i:s') : '00:00:00',
                    'raw_z'            => number_format((float)$t->raw_z, 4),
                    'raw_x'            => number_format((float)$t->raw_x, 4),
                    'raw_y'            => number_format((float)$t->raw_y, 4),
                    'p2p_z'            => number_format((float)$t->p2p_z, 4),
                    'wamp_z'           => number_format((float)$t->wamp_z, 2),
                    'rms_z'            => round((float)$t->rms_z, 4),
                    'rms_z_formatted'  => number_format((float)$t->rms_z, 4),
                    'condition'        => $cond,
                    'condition_text'   => $condText,
                    'cond_bg'          => $condBg,
                    'cond_color'       => $condColor,
                    'cond_border'      => $condBorder,
                    'confidence_score' => number_format((float)$t->confidence_score, 1),
                ];
            });

            $totalCount = VibrationTelemetry::count();
            $normalCount = VibrationTelemetry::where('condition', 'NORMAL')->count();
            $scoringCount = VibrationTelemetry::where('condition', 'SCORING')->count();
            $damagedCount = VibrationTelemetry::where('condition', 'DAMAGED')->count();
            $latestId = VibrationTelemetry::max('id') ?? $lastId;

            return response()->json([
                'success'       => true,
                'has_new'       => count($formattedRows) > 0,
                'latest_id'     => $latestId,
                'total_count'   => $totalCount,
                'normal_count'  => $normalCount,
                'scoring_count' => $scoringCount,
                'damaged_count' => $damagedCount,
                'new_records'   => $formattedRows,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ekspor Data Riwayat Telemetri ke CSV (GET /riwayat-pengukuran/export-csv)
     */
    public function exportCsv(Request $request)
    {
        $range = $request->query('range', 'all');
        $condition = strtolower($request->query('condition', 'all'));
        $query = VibrationTelemetry::orderBy('id', 'desc');

        if ($range === 'today') {
            $query->whereDate('recorded_at', Carbon::today());
        } elseif ($range === 'week' || $range === '7d') {
            $query->where('recorded_at', '>=', Carbon::now()->subDays(7));
        } elseif ($range === 'month' || $range === '30d') {
            $query->where('recorded_at', '>=', Carbon::now()->subDays(30));
        }

        if ($condition === 'normal') {
            $query->where('condition', 'NORMAL');
        } elseif ($condition === 'scoring' || $condition === 'terkikis') {
            $query->where('condition', 'SCORING');
        } elseif ($condition === 'damaged' || $condition === 'rusak') {
            $query->where('condition', 'DAMAGED');
        }

        $records = $query->take(20000)->get();
        $condSuffix = ($condition !== 'all') ? '_' . $condition : '';
        $fileName = 'gearsense_telemetri_' . $range . $condSuffix . '_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $columns = [
            'ID', 'Node Code', 'Timestamp (WIB)', 'Condition', 'P2P Z (g)', 'WAMP Z (g)',
            'WAMP Y (g)', 'RMS Z (g)', 'Kurtosis Z', 'Skewness Z', 'FIS Score',
            'Raw X (g)', 'Raw Y (g)', 'Raw Z (g)', 'Sampling Rate (Hz)'
        ];

        $callback = function() use ($records, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($records as $row) {
                $timeWib = $row->recorded_at ? $row->recorded_at->copy()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s') : '-';
                fputcsv($file, [
                    $row->id,
                    $row->node_code,
                    $timeWib,
                    $row->condition,
                    $row->p2p_z,
                    $row->wamp_z,
                    $row->wamp_y,
                    $row->rms_z,
                    $row->kurtosis_z,
                    $row->skewness_z,
                    $row->fis_score,
                    $row->raw_x,
                    $row->raw_y,
                    $row->raw_z,
                    $row->sampling_rate_hz,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Hitung perkiraan data telemetri yang cocok sebelum dihapus
     * (POST /riwayat-pengukuran/check-delete-count)
     */
    public function checkDeleteDataRange(Request $request)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $condition = strtolower($request->input('condition', 'all'));

            if (!$startDate || !$endDate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal mulai dan selesai harus diisi.',
                ], 422);
            }

            $start = Carbon::parse($startDate, 'Asia/Jakarta')->startOfDay();
            $end = Carbon::parse($endDate, 'Asia/Jakarta')->endOfDay();

            $query = VibrationTelemetry::whereBetween('recorded_at', [$start, $end]);

            if ($condition === 'normal') {
                $query->where('condition', 'NORMAL');
            } elseif ($condition === 'scoring' || $condition === 'terkikis') {
                $query->where('condition', 'SCORING');
            } elseif ($condition === 'damaged' || $condition === 'rusak') {
                $query->where('condition', 'DAMAGED');
            }

            $count = $query->count();

            return response()->json([
                'success' => true,
                'count'   => $count,
                'start'   => $start->format('d M Y'),
                'end'     => $end->format('d M Y'),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hapus data telemetri berdasarkan rentang waktu kalender & jenis data
     * (POST /riwayat-pengukuran/delete-data)
     */
    public function deleteDataRange(Request $request)
    {
        try {
            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date'   => 'required|date|after_or_equal:start_date',
                'condition'  => 'nullable|string',
            ]);

            $startDate = $validated['start_date'];
            $endDate = $validated['end_date'];
            $condition = strtolower($validated['condition'] ?? 'all');

            $start = Carbon::parse($startDate, 'Asia/Jakarta')->startOfDay();
            $end = Carbon::parse($endDate, 'Asia/Jakarta')->endOfDay();

            $query = VibrationTelemetry::whereBetween('recorded_at', [$start, $end]);

            if ($condition === 'normal') {
                $query->where('condition', 'NORMAL');
            } elseif ($condition === 'scoring' || $condition === 'terkikis') {
                $query->where('condition', 'SCORING');
            } elseif ($condition === 'damaged' || $condition === 'rusak') {
                $query->where('condition', 'DAMAGED');
            }

            $deletedCount = $query->delete();

            // Catat ke Log Audit Keamanan
            try {
                $operator = session('gearsense_user', 'Administrator');
                SecurityAuditLog::create([
                    'user_id'     => session('gearsense_user_id'),
                    'username'    => session('gearsense_username', $operator),
                    'event_type'  => 'TELEMETRY_DATA_PURGE',
                    'severity'    => 'WARNING',
                    'description' => "Menghapus {$deletedCount} data telemetri rentang {$startDate} s/d {$endDate} (Kondisi: " . strtoupper($condition) . ")",
                    'ip_address'  => $request->ip(),
                    'user_agent'  => $request->userAgent(),
                ]);
            } catch (\Throwable $e) {}

            return response()->json([
                'success'       => true,
                'message'       => "Berhasil menghapus " . number_format($deletedCount) . " rekaman data telemetri dari database.",
                'deleted_count' => $deletedCount,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper deteksi anomali insiden & notifikasi telegram otomatis
     */
    protected function handleIncidentDetection($nodeCode, $condition, $telemetry)
    {
        try {
            $severity = ($condition === 'DAMAGED') ? 'CRITICAL' : 'WARNING';
            $recommendation = ($condition === 'DAMAGED')
                ? 'Peringatan kerusakan parah roda gigi! Hentikan konveyor segera dan lakukan penggantian modul gigi.'
                : 'Peringatan keausan / erosi permukaan roda gigi terdeteksi. Lakukan pembersihan debris dan pelumasan ulang NLGI #2.';

            $code = 'INC-' . date('Y') . '-' . str_pad(IncidentLog::count() + 1, 3, '0', STR_PAD_LEFT);

            $incident = IncidentLog::create([
                'incident_code'        => $code,
                'node_code'            => $nodeCode,
                'severity'             => $severity,
                'condition_detected'   => $condition,
                'peak_acceleration_g'  => $telemetry->p2p_z,
                'dominant_frequency_hz'=> 48.5,
                'prescriptive_action'  => $recommendation,
                'started_at'           => Carbon::now(),
                'status'               => 'OPEN',
                'telegram_notified'    => false,
            ]);

            // Kirim notifikasi Telegram jika Bot Token & Chat ID terkonfigurasi (Non-Blocking Guard)
            $telegramConfig = TelegramConfig::first();
            if ($telegramConfig && !empty($telegramConfig->bot_token) && !empty($telegramConfig->status_monitoring)) {
                $token = trim($telegramConfig->bot_token);
                // Lewati jika token dummy/seeder agar tidak menyebabkan server PHP blocking
                $isDummyToken = (strpos($token, '123456:ABC') !== false || strlen($token) < 25);
                if (!$isDummyToken) {
                    $recipients = TelegramRecipient::all();
                    $message = "⚠️ *PERINGATAN ANOMALI GEARSENSE*\n\n"
                             . "• *Insiden:* `{$incident->incident_code}`\n"
                             . "• *Node:* `{$nodeCode}`\n"
                             . "• *Kondisi:* *{$condition}*\n"
                             . "• *Akselerasi P2P Z:* `{$telemetry->p2p_z} g`\n"
                             . "• *WAMP Z:* `{$telemetry->wamp_z} g`\n"
                             . "• *Tindakan CBM:* {$recommendation}\n"
                             . "• *Waktu:* " . Carbon::now('Asia/Jakarta')->format('d/m/Y H:i:s') . " WIB";

                    foreach ($recipients as $rec) {
                        try {
                            $res = Http::timeout(1)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                                'chat_id'    => $rec->chat_id,
                                'text'       => $message,
                                'parse_mode' => 'Markdown',
                            ]);
                            if (!$res->successful()) {
                                break; // Jika gagal, jangan loop recipient lain agar tidak lag
                            }
                        } catch (\Throwable $err) {
                            break; // Timeout/koneksi putus: hentikan loop seketika
                        }
                    }

                    $incident->update(['telegram_notified' => true]);
                }
            }
        } catch (\Throwable $e) {}
    }
}
