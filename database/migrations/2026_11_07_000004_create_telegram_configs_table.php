<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_configs', function (Blueprint $table) {
            $table->id();
            $table->string('bot_token')->nullable();
            $table->string('bot_username')->default('@GearsenseAlertBot');
            $table->float('alert_threshold_g')->default(0.85); // Ambang batas akselerasi getaran
            $table->boolean('status_monitoring')->default(true); // Sakelar aktif/nonaktif bot
            $table->boolean('notify_on_scoring')->default(true); // Alert saat scoring terdeteksi
            $table->boolean('notify_on_damaged')->default(true); // Alert saat gigi patah / damaged
            $table->boolean('notify_on_disconnect')->default(true); // Alert jika ESP32 offline
            $table->time('quiet_hours_start')->nullable(); // Jam hening alert
            $table->time('quiet_hours_end')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_configs');
    }
}
