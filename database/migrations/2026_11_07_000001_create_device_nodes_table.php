<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_nodes', function (Blueprint $table) {
            $table->id();
            $table->string('node_code')->unique(); // e.g. ESP32-GEARSENSE-01
            $table->string('node_name')->default('Rig Konveyor Transmisi Roda Gigi');
            $table->string('ip_address')->nullable();
            $table->string('mac_address')->nullable();
            $table->enum('connection_status', ['ONLINE', 'STANDBY', 'OFFLINE'])->default('STANDBY');
            $table->string('sensor_model')->default('ADXL345 Tri-Axis Digital Accelerometer');
            $table->string('i2c_address')->default('0x53');
            $table->string('firmware_version')->default('v1.4.2-esp32');
            $table->float('battery_level')->default(100.0);
            $table->float('temperature_c')->nullable()->default(38.0);
            $table->enum('motor_state', ['RUNNING', 'STOPPED', 'EMERGENCY_STOP'])->default('STOPPED');
            $table->integer('motor_speed_rpm')->default(1450);
            $table->integer('motor_speed_pwm')->default(0); // 0-255 PWM
            $table->timestamp('last_heartbeat_at')->nullable();
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
        Schema::dropIfExists('device_nodes');
    }
}
