<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'username',
        'event_type',
        'severity',
        'description',
        'ip_address',
        'user_agent',
        'payload_json',
    ];

    protected $casts = [
        'payload_json' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
