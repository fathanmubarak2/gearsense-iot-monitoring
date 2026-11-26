<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipient_name',
        'chat_id',
        'type',
        'is_active',
        'last_sent_at',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'last_sent_at' => 'datetime',
    ];
}
