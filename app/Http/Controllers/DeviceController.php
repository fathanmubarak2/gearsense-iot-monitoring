<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeviceNode;
use App\Models\SecurityAuditLog;
use Carbon\Carbon;

class DeviceController extends Controller
{
    /**
     * Tampilkan Halaman Kontrol Perangkat
     */
    public function index()
    {
        try {
            $device = DeviceNode::firstOrCreate(
                ['node_code' => 'ESP32-GEARSENSE-01'],
                [
                    'node_name'         => 'Node Sensor Utama Konveyor',
                    'ip_address'        => '192.168.1.105',
                    'mac_address'       => '34:85:18:9B:4C:D8',
                    'connection_status' => 'ONLINE',
                    'i2c_address'       => '0x53',
                    'motor_state'       => 'RUNNING',
                    'motor_speed_pwm'   => 180,
                    'motor_speed_rpm'   => 1250,
                    'last_heartbeat_at' => Carbon::now(),
                ]
            );
        } catch (\Throwable $e) {
            $device = null;
        }

        return view('kontrol-perangkat', compact('device'));
    }

    /**
     * Tampilkan Halaman Status Sensor & Node Hardware
     */
    public function statusSensor()
    {
        try {
            $device = DeviceNode::firstOrCreate(
                ['node_code' => 'ESP32-GEARSENSE-01'],
                [
                    'node_name'         => 'Node Sensor Utama Konveyor',
                    'ip_address'        => '192.168.1.105',
                    'mac_address'       => '34:85:18:9B:4C:D8',
                    'connection_status' => 'ONLINE',
                    'i2c_address'       => '0x53',
                    'motor_state'       => 'RUNNING',
                    'motor_speed_pwm'   => 180,
                    'motor_speed_rpm'   => 1250,
                    'last_heartbeat_at' => Carbon::now(),
                ]
            );
        } catch (\Throwable $e) {
            $device = null;
        }

        return view('status-sensor', compact('device'));
    }

    /**
     * Kontrol Saklar Motor Konveyor (POST /device/motor)
     */
    public function toggleMotor(Request $request)
    {
        $device = DeviceNode::firstOrCreate(['node_code' => 'ESP32-GEARSENSE-01']);
        $action = $request->input('action', 'toggle'); // start, stop, toggle

        if ($action === 'start') {
            $device->motor_state = 'RUNNING';
            $pwm = $device->motor_speed_pwm > 0 ? $device->motor_speed_pwm : 180;
            $device->motor_speed_pwm = $pwm;
            $device->motor_speed_rpm = (int) round(($pwm / 255) * 1700);
        } elseif ($action === 'stop') {
            $device->motor_state = 'STOPPED';
            $device->motor_speed_rpm = 0;
        } else {
            if ($device->motor_state === 'RUNNING') {
                $device->motor_state = 'STOPPED';
                $device->motor_speed_rpm = 0;
            } else {
                $device->motor_state = 'RUNNING';
                $pwm = $device->motor_speed_pwm > 0 ? $device->motor_speed_pwm : 180;
                $device->motor_speed_pwm = $pwm;
                $device->motor_speed_rpm = (int) round(($pwm / 255) * 1700);
            }
        }

        $device->save();

        // Audit Log
        try {
            SecurityAuditLog::create([
                'user_id'     => session('gearsense_user_id'),
                'username'    => session('gearsense_username', 'admin'),
                'event_type'  => 'DEVICE_MOTOR_CONTROL',
                'severity'    => 'INFO',
                'description' => 'Perintah motor konveyor diubah menjadi: ' . $device->motor_state . ' (' . $device->motor_speed_rpm . ' RPM).',
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'payload_json'=> ['motor_state' => $device->motor_state, 'rpm' => $device->motor_speed_rpm],
            ]);
        } catch (\Throwable $e) {}

        return response()->json([
            'success'      => true,
            'motor_state'  => $device->motor_state,
            'motor_status' => $device->motor_state,
            'speed_rpm'    => $device->motor_speed_rpm,
            'message'      => 'Status motor konveyor berhasil diubah menjadi ' . $device->motor_state . '.',
        ]);
    }

    /**
     * Atur Kecepatan Motor PWM (POST /device/speed)
     */
    public function updateSpeed(Request $request)
    {
        $request->validate([
            'speed' => 'required|integer|min:0|max:255',
        ]);

        $device = DeviceNode::firstOrCreate(['node_code' => 'ESP32-GEARSENSE-01']);
        $pwm = (int) $request->speed;
        $device->motor_speed_pwm = $pwm;
        if ($device->motor_state === 'RUNNING') {
            $device->motor_speed_rpm = (int) round(($pwm / 255) * 1700);
        } else {
            $device->motor_speed_rpm = 0;
        }
        $device->save();

        return response()->json([
            'success'   => true,
            'speed_pwm' => $device->motor_speed_pwm,
            'speed_rpm' => $device->motor_speed_rpm,
            'message'   => 'Kecepatan motor konveyor diperbarui: ' . $device->motor_speed_pwm . ' PWM (~' . $device->motor_speed_rpm . ' RPM).',
        ]);
    }

    /**
     * Tombol Penghentian Darurat (Emergency Stop) (POST /device/emergency-stop)
     */
    public function emergencyStop(Request $request)
    {
        $device = DeviceNode::firstOrCreate(['node_code' => 'ESP32-GEARSENSE-01']);
        $device->motor_state = 'STOPPED';
        $device->motor_speed_pwm = 0;
        $device->motor_speed_rpm = 0;
        $device->save();

        // Catat Insiden Kritis di Audit Log
        try {
            SecurityAuditLog::create([
                'user_id'     => session('gearsense_user_id'),
                'username'    => session('gearsense_username', 'admin'),
                'event_type'  => 'EMERGENCY_STOP',
                'severity'    => 'DANGER',
                'description' => 'TOMBOL EMERGENCY STOP DITEKAN! Motor konveyor dihentikan paksa ke 0 PWM.',
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'payload_json'=> ['action' => 'emergency_trip', 'source' => 'web_dashboard'],
            ]);
        } catch (\Throwable $e) {}

        return response()->json([
            'success' => true,
            'message' => 'PENGHENTIAN DARURAT DIAKTIFKAN: Motor konveyor telah dihentikan secara instan.',
            'device'  => $device,
        ]);
    }

    /**
     * Atur Frekuensi Sampling Sensor (POST /device/sampling-rate)
     */
    public function setSamplingRate(Request $request)
    {
        $device = DeviceNode::firstOrCreate(['node_code' => 'ESP32-GEARSENSE-01']);
        $rate = (int) $request->input('rate', 200);

        try {
            SecurityAuditLog::create([
                'user_id'     => session('gearsense_user_id'),
                'username'    => session('gearsense_username', 'admin'),
                'event_type'  => 'SAMPLING_RATE_UPDATE',
                'severity'    => 'INFO',
                'description' => 'Frekuensi sampling telemetri ADXL345 disetel ke ' . $rate . ' Hz.',
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);
        } catch (\Throwable $e) {}

        return response()->json([
            'success' => true,
            'rate'    => $rate,
            'message' => 'Frekuensi sampling ADXL345 diperbarui menjadi ' . $rate . ' Hz.',
        ]);
    }

    /**
     * Reboot Perangkat ESP32 (POST /device/reboot)
     */
    public function reboot(Request $request)
    {
        $device = DeviceNode::firstOrCreate(['node_code' => 'ESP32-GEARSENSE-01']);
        $device->connection_status = 'STANDBY';
        $device->save();

        try {
            SecurityAuditLog::create([
                'user_id'     => session('gearsense_user_id'),
                'username'    => session('gearsense_username', 'admin'),
                'event_type'  => 'DEVICE_REBOOT',
                'severity'    => 'WARNING',
                'description' => 'Perintah restart lunak (soft reboot) dikirimkan ke modul ESP32.',
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);
        } catch (\Throwable $e) {}

        return response()->json([
            'success' => true,
            'message' => 'Perintah soft restart telah dikirimkan ke modul ESP32-GEARSENSE-01.',
        ]);
    }
}
