<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceNode extends Model
{
    use HasFactory;

    protected $fillable = [
        'node_code',
        'node_name',
        'ip_address',
        'mac_address',
        'connection_status',
        'sensor_model',
        'i2c_address',
        'firmware_version',
        'battery_level',
        'temperature_c',
        'motor_state',
        'motor_speed_rpm',
        'motor_speed_pwm',
        'last_heartbeat_at',
    ];

    protected $casts = [
        'last_heartbeat_at' => 'datetime',
        'battery_level'     => 'float',
        'temperature_c'     => 'float',
        'motor_speed_rpm'   => 'integer',
        'motor_speed_pwm'   => 'integer',
    ];

    /**
     * Relasi ke rekaman telemetri getaran node ini
     */
    public function telemetries()
    {
        return $this->hasMany(VibrationTelemetry::class, 'node_code', 'node_code');
    }

    /**
     * Relasi ke log insiden anomali node ini
     */
    public function incidentLogs()
    {
        return $this->hasMany(IncidentLog::class, 'node_code', 'node_code');
    }
}
