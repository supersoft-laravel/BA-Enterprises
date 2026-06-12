<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1 — Add customer_id (nullable) to vehicle_cases
        Schema::table('vehicle_cases', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->after('id');
        });

        // Step 2 — Add customer_id (nullable) to billings
        Schema::table('billings', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->after('vehicle_case_id');
        });

        // Step 3 — Add vehicle_case_id (nullable) to billing_items
        Schema::table('billing_items', function (Blueprint $table) {
            $table->unsignedBigInteger('vehicle_case_id')->nullable()->after('billing_id');
        });

        // Step 4 — Build customers from distinct party_mobile values in vehicle_cases
        $rows = DB::table('vehicle_cases')
            ->select('party_name', 'party_mobile')
            ->whereNotNull('party_mobile')
            ->where('party_mobile', '!=', '')
            ->orderBy('id')
            ->get();

        $seen    = [];
        $counter = 1;
        $now     = now()->toDateTimeString();

        foreach ($rows as $row) {
            if (isset($seen[$row->party_mobile])) {
                continue;
            }
            $seen[$row->party_mobile] = true;

            DB::table('customers')->insert([
                'customer_code' => 'CUST-' . str_pad($counter, 4, '0', STR_PAD_LEFT),
                'name'          => $row->party_name,
                'mobile'        => $row->party_mobile,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
            $counter++;
        }

        // Step 5 — Backfill vehicle_cases.customer_id by matching party_mobile
        $customers = DB::table('customers')->select('id', 'mobile')->get();
        foreach ($customers as $customer) {
            DB::table('vehicle_cases')
                ->where('party_mobile', $customer->mobile)
                ->update(['customer_id' => $customer->id]);
        }

        // Step 6 — Backfill billings.customer_id through vehicle_cases
        DB::statement('
            UPDATE billings b
            INNER JOIN vehicle_cases vc ON b.vehicle_case_id = vc.id
            SET b.customer_id = vc.customer_id
            WHERE vc.customer_id IS NOT NULL
        ');

        // Step 7 — Backfill billing_items.vehicle_case_id through billings
        DB::statement('
            UPDATE billing_items bi
            INNER JOIN billings b ON bi.billing_id = b.id
            SET bi.vehicle_case_id = b.vehicle_case_id
            WHERE b.vehicle_case_id IS NOT NULL
        ');

        // Step 8 — Add FK constraints after all data is in place
        Schema::table('vehicle_cases', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
        });

        Schema::table('billings', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
        });

        Schema::table('billing_items', function (Blueprint $table) {
            $table->foreign('vehicle_case_id')->references('id')->on('vehicle_cases')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('billing_items', function (Blueprint $table) {
            $table->dropForeign(['vehicle_case_id']);
            $table->dropColumn('vehicle_case_id');
        });

        Schema::table('billings', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
        });

        Schema::table('vehicle_cases', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
        });
    }
};
