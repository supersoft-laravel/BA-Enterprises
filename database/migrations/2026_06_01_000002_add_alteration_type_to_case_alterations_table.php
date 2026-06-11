<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('case_alterations', function (Blueprint $table) {
            $table->string('alteration_type')->nullable()->after('vehicle_case_id');
        });
    }

    public function down(): void
    {
        Schema::table('case_alterations', function (Blueprint $table) {
            $table->dropColumn('alteration_type');
        });
    }
};
