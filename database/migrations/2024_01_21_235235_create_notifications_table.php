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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('question');
            $table->text('options');
            $table->dateTime('sent_at');
            $table->foreignUuid('treatment_uuid')->references('uuid')->on('treatments');
            $table->foreignUuid('question_uuid')->references('uuid')->on('questions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
