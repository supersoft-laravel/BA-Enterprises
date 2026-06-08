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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->constrained('other_users')->cascadeOnDelete();
            $table->double('subtotal', 10)->default(0);
            $table->double('tax', 10)->default(0);
            $table->double('total', 10)->default(0);
            $table->date('invoice_date')->default(now());
            $table->date('due_date')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['paid', 'unpaid', 'overdue'])->default('unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
