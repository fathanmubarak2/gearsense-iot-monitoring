<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramRecipientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_recipients', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_name'); // e.g. Ruang Kontrol Operator Lab
            $table->string('chat_id'); // e.g. -100123456789 atau ID akun personal
            $table->enum('type', ['PERSONAL', 'GROUP', 'CHANNEL'])->default('PERSONAL');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_sent_at')->nullable();
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
        Schema::dropIfExists('telegram_recipients');
    }
}
