<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('case_permits', function (Blueprint $table) {
            $table->text('province_status')->nullable()->after('province');
        });
    }

    public function down(): void
    {
        Schema::table('case_permits', function (Blueprint $table) {
            $table->dropColumn('province_status');
        });
    }
};
