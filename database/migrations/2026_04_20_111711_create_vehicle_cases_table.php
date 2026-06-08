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
        Schema::create('vehicle_cases', function (Blueprint $table) {
            $table->id();

            // Common Info
            $table->string('city')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->string('vehicle_make')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->string('engine_no')->nullable();
            $table->string('chassis_no')->nullable();

            // Party Info
            $table->string('party_name');
            $table->string('party_mobile');

            // Case Info
            $table->date('case_date')->nullable();
            $table->text('comment')->nullable();

            // Tracking
            $table->timestamp('submitted_at')->nullable();

            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_cases');
    }
};
