<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IncidentLog;
use Carbon\Carbon;

class IncidentLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $incidents = [
            [
                'incident_code' => 'INC-2026-001',
                'node_code' => 'ESP32-GEARSENSE-01',
                'severity' => 'WARNING',
                'condition_detected' => 'SCORING',
                'peak_acceleration_g' => 1.84,
                'dominant_frequency_hz' => 45.2,
                'prescriptive_action' => 'Pembersihan debris sproket dan pelumasan ulang sintetis NLGI #2. Pemeriksaan ketegangan rantai konveyor.',
                'started_at' => $now->copy()->subDays(3)->format('Y-m-d H:i:s'),
                'resolved_at' => $now->copy()->subDays(3)->addHours(2)->format('Y-m-d H:i:s'),
                'duration_seconds' => 7200,
                'status' => 'RESOLVED',
                'resolved_by' => 'Adelia (Administrator)',
                'telegram_notified' => true,
            ],
            [
                'incident_code' => 'INC-2026-002',
                'node_code' => 'ESP32-GEARSENSE-01',
                'severity' => 'CRITICAL',
                'condition_detected' => 'DAMAGED',
                'peak_acceleration_g' => 3.12,
                'dominant_frequency_hz' => 112.8,
                'prescriptive_action' => 'Keausan parah pada modul gigi pinion. Penghentian darurat motor dan penggantian gear set.',
                'started_at' => $now->copy()->subDays(1)->format('Y-m-d H:i:s'),
                'resolved_at' => $now->copy()->subDays(1)->addHours(1)->addMinutes(45)->format('Y-m-d H:i:s'),
                'duration_seconds' => 6300,
                'status' => 'RESOLVED',
                'resolved_by' => 'Fathan (Administrator)',
                'telegram_notified' => true,
            ],
            [
                'incident_code' => 'INC-2026-003',
                'node_code' => 'ESP32-GEARSENSE-01',
                'severity' => 'WARNING',
                'condition_detected' => 'SCORING',
                'peak_acceleration_g' => 1.76,
                'dominant_frequency_hz' => 48.6,
                'prescriptive_action' => 'Lonjakan amplitudo WAMP Z terdeteksi di atas threshold 0.25 g. Rekomendasikan pengecekan alignment dan getaran bearing.',
                'started_at' => $now->copy()->subHours(1)->format('Y-m-d H:i:s'),
                'resolved_at' => null,
                'duration_seconds' => null,
                'status' => 'OPEN',
                'resolved_by' => null,
                'telegram_notified' => true,
            ],
        ];

        foreach ($incidents as $inc) {
            IncidentLog::updateOrCreate(
                ['incident_code' => $inc['incident_code']],
                $inc
            );
        }
    }
}
