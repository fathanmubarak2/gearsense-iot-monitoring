<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_token',
        'bot_username',
        'alert_threshold_g',
        'status_monitoring',
        'notify_on_scoring',
        'notify_on_damaged',
        'notify_on_disconnect',
        'quiet_hours_start',
        'quiet_hours_end',
    ];

    protected $casts = [
        'alert_threshold_g'    => 'float',
        'status_monitoring'    => 'boolean',
        'notify_on_scoring'    => 'boolean',
        'notify_on_damaged'    => 'boolean',
        'notify_on_disconnect' => 'boolean',
    ];

    public function recipients()
    {
        return $this->hasMany(TelegramRecipient::class);
    }
}
