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
        Schema::table('billings', function (Blueprint $table) {
            $table->decimal('adjustment_amount', 10, 2)->default(0)->after('remaining_amount');
            $table->string('adjustment_note')->nullable()->after('adjustment_amount');
        });
    }

    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropColumn(['adjustment_amount', 'adjustment_note']);
        });
    }
};
