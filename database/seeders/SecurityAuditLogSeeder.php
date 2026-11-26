<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SecurityAuditLog;
use App\Models\User;
use Carbon\Carbon;

class SecurityAuditLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $admin = User::where('role', 'ADMINISTRATOR')->first();

        $logs = [
            [
                'user_id' => $admin ? $admin->id : 1,
                'username' => $admin ? $admin->username : 'adelia',
                'event_type' => 'AUTH_LOGIN',
                'severity' => 'INFO',
                'description' => 'Login otentikasi berhasil sebagai ADMINISTRATOR.',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'payload_json' => ['action' => 'login', 'method' => 'web_form'],
                'created_at' => $now->copy()->subHours(5),
            ],
            [
                'user_id' => $admin ? $admin->id : 1,
                'username' => $admin ? $admin->username : 'adelia',
                'event_type' => 'CONFIG_UPDATE',
                'severity' => 'WARNING',
                'description' => 'Pembaruan threshold notifikasi getaran Telegram menjadi 1.50 g.',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'payload_json' => ['threshold_g' => 1.5, 'previous_threshold_g' => 1.8],
                'created_at' => $now->copy()->subHours(3),
            ],
            [
                'user_id' => null,
                'username' => 'guest',
                'event_type' => 'AUTH_FAILED',
                'severity' => 'WARNING',
                'description' => 'Percobaan login gagal untuk username: unknown_operator (Password salah).',
                'ip_address' => '192.168.1.105',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X)',
                'payload_json' => ['attempted_username' => 'unknown_operator'],
                'created_at' => $now->copy()->subHours(2),
            ],
            [
                'user_id' => $admin ? $admin->id : 1,
                'username' => $admin ? $admin->username : 'adelia',
                'event_type' => 'DEVICE_REBOOT',
                'severity' => 'INFO',
                'description' => 'Perintah soft restart dikirimkan ke node ESP32-GEARSENSE-01 via MQTT.',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'payload_json' => ['target_node' => 'ESP32-GEARSENSE-01'],
                'created_at' => $now->copy()->subMinutes(30),
            ],
        ];

        foreach ($logs as $log) {
            SecurityAuditLog::create($log);
        }
    }
}
