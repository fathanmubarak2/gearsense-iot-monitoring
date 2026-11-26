<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VibrationTelemetry;
use Carbon\Carbon;

class VibrationTelemetrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Bersihkan data lama jika ada
        VibrationTelemetry::truncate();

        $nodeId = 'ESP32-GEARSENSE-01';
        $now = Carbon::now();

        $telemetries = [];

        // Buat 60 data poin time-series (1 per menit ke belakang)
        for ($i = 59; $i >= 0; $i--) {
            $time = $now->copy()->subMinutes($i);

            // Simulasi beberapa variasi anomali realistis
            if ($i >= 15 && $i <= 18) {
                // Skenario Scoring sesaat pada menit ke-15..18 yang lalu
                $cond = 'SCORING';
                $rawZ = 0.98 + (sin($i) * 0.08);
                $p2pZ = 24.5 + (sin($i) * 2.1);
                $wampZ = 8.5;
                $fis = 62.0;
                $conf = 98.4;
            } elseif ($i >= 35 && $i <= 36) {
                // Skenario Damaged singkat saat uji coba getaran
                $cond = 'DAMAGED';
                $rawZ = 1.35 + (sin($i) * 0.12);
                $p2pZ = 39.2 + (sin($i) * 3.0);
                $wampZ = 14.0;
                $fis = 88.5;
                $conf = 99.1;
            } else {
                // Skenario Normal beroperasi optimal
                $cond = 'NORMAL';
                $rawZ = 0.84 + (sin($i) * 0.03);
                $p2pZ = 16.0 + (sin($i) * 0.8);
                $wampZ = 4.0 + (cos($i) * 0.5);
                $fis = 18.0 + (sin($i) * 2.0);
                $conf = 100.0;
            }

            $rawX = 0.01 + (cos($i) * 0.005);
            $rawY = 0.02 + (sin($i) * 0.008);

            $telemetries[] = [
                'node_code'        => $nodeId,
                'raw_z'            => round($rawZ, 3),
                'raw_x'            => round($rawX, 3),
                'raw_y'            => round($rawY, 3),
                'p2p_z'            => round($p2pZ, 2),
                'p2p_x'            => round(3.5 + (sin($i) * 0.3), 2),
                'p2p_y'            => round(4.2 + (cos($i) * 0.4), 2),
                'wamp_z'           => round($wampZ, 2),
                'wamp_y'           => round(6.0 + (sin($i) * 0.3), 2),
                'rms_z'            => round($rawZ * 0.98, 3),
                'kurtosis_z'       => round(3.1 + (sin($i) * 0.2), 2),
                'skewness_z'       => round(0.12 + (cos($i) * 0.02), 3),
                'condition'        => $cond,
                'confidence_score' => $conf,
                'fis_score'        => round($fis, 1),
                'sampling_rate_hz' => 50,
                'recorded_at'      => $time,
                'created_at'       => $time,
                'updated_at'       => $time,
            ];
        }

        // Insert sekaligus (bulk)
        VibrationTelemetry::insert($telemetries);
    }
}
