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
        Schema::create('case_file_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_case_id')->constrained('vehicle_cases')->cascadeOnDelete();

            // From
            $table->string('from_name');
            $table->string('from_s_o');
            $table->string('from_nic');

            // To
            $table->string('to_name');
            $table->string('to_s_o');
            $table->string('to_nic');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_file_returns');
    }
};
