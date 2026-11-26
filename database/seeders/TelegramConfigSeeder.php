<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TelegramConfig;
use App\Models\TelegramRecipient;

class TelegramConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $config = TelegramConfig::updateOrCreate(
            ['id' => 1],
            [
                'bot_token'            => '7483920194:AAFlk90XyZA83_DemoBotGearsenseYarsi2026',
                'bot_username'         => '@GearsenseAlertBot',
                'alert_threshold_g'    => 0.85,
                'status_monitoring'    => true,
                'notify_on_scoring'    => true,
                'notify_on_damaged'    => true,
                'notify_on_disconnect' => true,
                'quiet_hours_start'    => '23:00',
                'quiet_hours_end'      => '05:00',
            ]
        );

        $recipients = [
            [
                'recipient_name' => 'Fathan Mubarak (Lab IoT Lead)',
                'chat_id'        => '684920184',
                'type'           => 'PERSONAL',
                'is_active'      => true,
                'last_sent_at'   => now()->subHours(1),
            ],
            [
                'recipient_name' => 'Grup Monitoring Getaran Lab YARSI',
                'chat_id'        => '-1002349081234',
                'type'           => 'GROUP',
                'is_active'      => true,
                'last_sent_at'   => now()->subHours(3),
            ],
            [
                'recipient_name' => 'Ruang Kontrol Operator Lab',
                'chat_id'        => '-1003491823901',
                'type'           => 'GROUP',
                'is_active'      => true,
                'last_sent_at'   => now()->subDay(),
            ],
        ];

        foreach ($recipients as $recipient) {
            TelegramRecipient::updateOrCreate(
                ['chat_id' => $recipient['chat_id']],
                $recipient
            );
        }
    }
}
