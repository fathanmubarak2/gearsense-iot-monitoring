<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncidentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incident_logs', function (Blueprint $table) {
            $table->id();
            $table->string('incident_code')->unique(); // e.g. INC-2026-001
            $table->string('node_code')->default('ESP32-GEARSENSE-01')->index();
            $table->enum('severity', ['INFO', 'WARNING', 'CRITICAL'])->default('WARNING');
            $table->enum('condition_detected', ['NORMAL', 'SCORING', 'DAMAGED']);
            $table->float('peak_acceleration_g'); // Akselerasi getaran saat anomali
            $table->float('dominant_frequency_hz')->nullable(); // Frekuensi FFT dominan
            $table->text('prescriptive_action'); // Rekomendasi tindakan perbaikan (CBM)
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('resolved_at')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->enum('status', ['OPEN', 'INVESTIGATING', 'RESOLVED'])->default('OPEN');
            $table->string('resolved_by')->nullable();
            $table->boolean('telegram_notified')->default(false);
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
        Schema::dropIfExists('incident_logs');
    }
}
