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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_case_id')->constrained('vehicle_cases')->cascadeOnDelete();
            $table->enum('billing_type', ['local', 'out_of_city'])->default('local');
            $table->string('bill_no')->nullable()->unique();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2);
            $table->decimal('remaining_amount', 10, 2);
            $table->date('billing_date');
            $table->string('billing_name')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['unpaid', 'paid', 'partial'])->default('unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};
