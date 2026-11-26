<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeviceNode;

class DeviceNodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DeviceNode::updateOrCreate(
            ['node_code' => 'ESP32-GEARSENSE-01'],
            [
                'node_name'         => 'Rig Konveyor Transmisi Roda Gigi Utama',
                'ip_address'        => '192.168.1.108',
                'mac_address'       => '24:6F:28:B4:7A:1C',
                'connection_status' => 'ONLINE',
                'sensor_model'      => 'ADXL345 Tri-Axis Digital Accelerometer',
                'i2c_address'       => '0x53',
                'firmware_version'  => 'v1.4.2-esp32',
                'battery_level'     => 100.0,
                'temperature_c'     => 38.2,
                'motor_state'       => 'RUNNING',
                'motor_speed_rpm'   => 1450,
                'motor_speed_pwm'   => 210,
                'last_heartbeat_at' => now(),
            ]
        );
    }
}
