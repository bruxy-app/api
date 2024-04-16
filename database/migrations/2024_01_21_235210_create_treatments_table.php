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
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->integer('minimum_percentage');
            $table->integer('questions_per_day');
            $table->string('status');
            $table->dateTime('actual_end')->nullable();
            $table->foreignUuid('patient_uuid')->references('uuid')->on('patients');
            $table->foreignUuid('responsible_uuid')->references('uuid')->on('users');
            $table->foreignUuid('clinic_uuid')->references('uuid')->on('clinics');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
