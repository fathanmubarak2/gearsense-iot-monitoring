<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVibrationTelemetriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vibration_telemetries', function (Blueprint $table) {
            $table->id();
            $table->string('node_code')->default('ESP32-GEARSENSE-01')->index();
            $table->float('raw_z')->default(0.85); // Percepatan getaran Z (Radial)
            $table->float('raw_x')->default(0.01); // Percepatan getaran X (Aksial)
            $table->float('raw_y')->default(0.02); // Percepatan getaran Y (Tangensial)
            $table->float('p2p_z')->default(16.0); // Peak-to-Peak Radial
            $table->float('p2p_x')->default(3.5);  // Peak-to-Peak Aksial
            $table->float('p2p_y')->default(4.2);  // Peak-to-Peak Tangensial
            $table->float('wamp_z')->default(4.0); // Wilson Amplitude Z
            $table->float('wamp_y')->default(6.0); // Wilson Amplitude Y
            $table->float('rms_z')->default(0.85);  // Root Mean Square Z
            $table->float('kurtosis_z')->nullable()->default(3.1);
            $table->float('skewness_z')->nullable()->default(0.12);
            $table->enum('condition', ['NORMAL', 'SCORING', 'DAMAGED'])->default('NORMAL')->index();
            $table->float('confidence_score')->default(100.0); // % akurasi model
            $table->float('fis_score')->default(18.0); // Fuzzy Inference System 0-100
            $table->integer('sampling_rate_hz')->default(50); // Frekuensi sampling sensor
            $table->timestamp('recorded_at')->useCurrent()->index();
            $table->timestamps();

            // Composite index untuk kecepatan kueri time-series grafik
            $table->index(['node_code', 'recorded_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vibration_telemetries');
    }
}
