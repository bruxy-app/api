<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_responses', function (Blueprint $table) {
            $table->id();
            $table->text('response');
            $table->dateTime('response_at');
            $table->foreignUuid('notification_uuid')->references('uuid')->on('notifications');
            $table->foreignUuid('treatment_uuid')->references('uuid')->on('treatments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_responses');
    }
};
