<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VibrationTelemetry extends Model
{
    use HasFactory;

    protected $fillable = [
        'node_code',
        'raw_z',
        'raw_x',
        'raw_y',
        'p2p_z',
        'p2p_x',
        'p2p_y',
        'wamp_z',
        'wamp_y',
        'rms_z',
        'kurtosis_z',
        'skewness_z',
        'condition',
        'confidence_score',
        'fis_score',
        'sampling_rate_hz',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at'      => 'datetime',
        'raw_z'            => 'float',
        'raw_x'            => 'float',
        'raw_y'            => 'float',
        'p2p_z'            => 'float',
        'p2p_x'            => 'float',
        'p2p_y'            => 'float',
        'wamp_z'           => 'float',
        'wamp_y'           => 'float',
        'rms_z'            => 'float',
        'confidence_score' => 'float',
        'fis_score'        => 'float',
        'sampling_rate_hz' => 'integer',
    ];

    /**
     * Relasi ke perangkat node induk
     */
    public function deviceNode()
    {
        return $this->belongsTo(DeviceNode::class, 'node_code', 'node_code');
    }
}
