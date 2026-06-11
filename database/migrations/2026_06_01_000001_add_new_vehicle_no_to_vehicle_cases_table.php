<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicle_cases', function (Blueprint $table) {
            $table->string('new_vehicle_no')->nullable()->after('vehicle_no');
        });
    }

    public function down(): void
    {
        Schema::table('vehicle_cases', function (Blueprint $table) {
            $table->dropColumn('new_vehicle_no');
        });
    }
};
