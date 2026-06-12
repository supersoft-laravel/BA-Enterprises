<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $guard = 'web';
        $now   = now()->toDateTimeString();

        $permissions = [
            'view customer',
            'create customer',
            'update customer',
            'delete customer',
        ];

        // Insert permissions (skip if already exist)
        foreach ($permissions as $name) {
            DB::table('permissions')->insertOrIgnore([
                'name'       => $name,
                'guard_name' => $guard,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Assign all 4 to super-admin role
        $superAdmin = DB::table('roles')->where('name', 'super-admin')->where('guard_name', $guard)->first();
        if ($superAdmin) {
            foreach ($permissions as $name) {
                $perm = DB::table('permissions')
                    ->where('name', $name)
                    ->where('guard_name', $guard)
                    ->first();
                if ($perm) {
                    DB::table('role_has_permissions')->insertOrIgnore([
                        'permission_id' => $perm->id,
                        'role_id'       => $superAdmin->id,
                    ]);
                }
            }
        }

        // Assign view + create + update customer to admin role
        $admin = DB::table('roles')->where('name', 'admin')->where('guard_name', $guard)->first();
        if ($admin) {
            foreach (['view customer', 'create customer', 'update customer'] as $name) {
                $perm = DB::table('permissions')
                    ->where('name', $name)
                    ->where('guard_name', $guard)
                    ->first();
                if ($perm) {
                    DB::table('role_has_permissions')->insertOrIgnore([
                        'permission_id' => $perm->id,
                        'role_id'       => $admin->id,
                    ]);
                }
            }
        }

        // Clear Spatie permission cache so new permissions take effect immediately
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function down(): void
    {
        $guard = 'web';

        $permIds = DB::table('permissions')
            ->whereIn('name', ['view customer', 'create customer', 'update customer', 'delete customer'])
            ->where('guard_name', $guard)
            ->pluck('id');

        DB::table('role_has_permissions')->whereIn('permission_id', $permIds)->delete();
        DB::table('permissions')
            ->whereIn('name', ['view customer', 'create customer', 'update customer', 'delete customer'])
            ->where('guard_name', $guard)
            ->delete();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
