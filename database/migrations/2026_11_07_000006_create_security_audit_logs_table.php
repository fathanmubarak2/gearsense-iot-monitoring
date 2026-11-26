<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecurityAuditLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('security_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('username')->nullable();
            $table->string('event_type')->index(); // AUTH_LOGIN, DEVICE_CONTROL, EMERGENCY_STOP, dll.
            $table->enum('severity', ['INFO', 'WARNING', 'DANGER'])->default('INFO')->index();
            $table->text('description');
            $table->string('ip_address')->default('127.0.0.1');
            $table->string('user_agent')->nullable();
            $table->json('payload_json')->nullable();
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
        Schema::dropIfExists('security_audit_logs');
    }
}
