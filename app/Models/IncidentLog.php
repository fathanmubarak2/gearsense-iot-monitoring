<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_code',
        'node_code',
        'severity',
        'condition_detected',
        'peak_acceleration_g',
        'dominant_frequency_hz',
        'prescriptive_action',
        'started_at',
        'resolved_at',
        'duration_seconds',
        'status',
        'resolved_by',
        'telegram_notified',
    ];

    protected $casts = [
        'started_at'            => 'datetime',
        'resolved_at'           => 'datetime',
        'peak_acceleration_g'   => 'float',
        'dominant_frequency_hz' => 'float',
        'duration_seconds'      => 'integer',
        'telegram_notified'     => 'boolean',
    ];

    public function deviceNode()
    {
        return $this->belongsTo(DeviceNode::class, 'node_code', 'node_code');
    }
}
