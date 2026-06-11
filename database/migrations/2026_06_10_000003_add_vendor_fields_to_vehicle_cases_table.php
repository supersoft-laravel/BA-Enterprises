<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicle_cases', function (Blueprint $table) {
            $table->string('vendor_name')->nullable()->after('party_mobile');
            $table->string('vendor_mobile')->nullable()->after('vendor_name');
        });
    }

    public function down(): void
    {
        Schema::table('vehicle_cases', function (Blueprint $table) {
            $table->dropColumn(['vendor_name', 'vendor_mobile']);
        });
    }
};
