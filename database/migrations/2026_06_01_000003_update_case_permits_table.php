<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change enum to string so any value is accepted
        DB::statement("ALTER TABLE case_permits MODIFY type VARCHAR(50) NULL");

        Schema::table('case_permits', function (Blueprint $table) {
            $table->string('province')->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('case_permits', function (Blueprint $table) {
            $table->dropColumn('province');
        });
        DB::statement("ALTER TABLE case_permits MODIFY type ENUM('RTA','PTA','Others') NULL");
    }
};
