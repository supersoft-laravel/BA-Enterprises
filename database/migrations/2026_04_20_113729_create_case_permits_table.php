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
        Schema::create('case_permits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_case_id')->constrained('vehicle_cases')->cascadeOnDelete();
            $table->enum('type', ['RTA', 'PTA', 'Others'])->nullable();
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_permits');
    }
};
