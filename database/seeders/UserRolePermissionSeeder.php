<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        Permission::firstOrCreate(['name' => 'view role']);
        Permission::firstOrCreate(['name' => 'create role']);
        Permission::firstOrCreate(['name' => 'update role']);
        Permission::firstOrCreate(['name' => 'delete role']);

        Permission::firstOrCreate(['name' => 'view permission']);
        Permission::firstOrCreate(['name' => 'create permission']);
        Permission::firstOrCreate(['name' => 'update permission']);
        Permission::firstOrCreate(['name' => 'delete permission']);

        Permission::firstOrCreate(['name' => 'view user']);
        Permission::firstOrCreate(['name' => 'create user']);
        Permission::firstOrCreate(['name' => 'update user']);
        Permission::firstOrCreate(['name' => 'delete user']);

        Permission::firstOrCreate(['name' => 'view staff']);
        Permission::firstOrCreate(['name' => 'create staff']);
        Permission::firstOrCreate(['name' => 'update staff']);
        Permission::firstOrCreate(['name' => 'delete staff']);

        Permission::firstOrCreate(['name' => 'view archived user']);
        Permission::firstOrCreate(['name' => 'create archived user']);
        Permission::firstOrCreate(['name' => 'update archived user']);
        Permission::firstOrCreate(['name' => 'delete archived user']);

        Permission::firstOrCreate(['name' => 'view setting']);
        Permission::firstOrCreate(['name' => 'create setting']);
        Permission::firstOrCreate(['name' => 'update setting']);
        Permission::firstOrCreate(['name' => 'delete setting']);

        Permission::firstOrCreate(['name' => 'view transfer']);
        Permission::firstOrCreate(['name' => 'create transfer']);
        Permission::firstOrCreate(['name' => 'update transfer']);
        Permission::firstOrCreate(['name' => 'delete transfer']);

        Permission::firstOrCreate(['name' => 'view alteration']);
        Permission::firstOrCreate(['name' => 'create alteration']);
        Permission::firstOrCreate(['name' => 'update alteration']);
        Permission::firstOrCreate(['name' => 'delete alteration']);

        Permission::firstOrCreate(['name' => 'view tax']);
        Permission::firstOrCreate(['name' => 'create tax']);
        Permission::firstOrCreate(['name' => 'update tax']);
        Permission::firstOrCreate(['name' => 'delete tax']);

        Permission::firstOrCreate(['name' => 'view insurance']);
        Permission::firstOrCreate(['name' => 'create insurance']);
        Permission::firstOrCreate(['name' => 'update insurance']);
        Permission::firstOrCreate(['name' => 'delete insurance']);

        Permission::firstOrCreate(['name' => 'view permit']);
        Permission::firstOrCreate(['name' => 'create permit']);
        Permission::firstOrCreate(['name' => 'update permit']);
        Permission::firstOrCreate(['name' => 'delete permit']);

        Permission::firstOrCreate(['name' => 'view fitness']);
        Permission::firstOrCreate(['name' => 'create fitness']);
        Permission::firstOrCreate(['name' => 'update fitness']);
        Permission::firstOrCreate(['name' => 'delete fitness']);

        Permission::firstOrCreate(['name' => 'view invoice']);
        Permission::firstOrCreate(['name' => 'create invoice']);
        Permission::firstOrCreate(['name' => 'update invoice']);
        Permission::firstOrCreate(['name' => 'delete invoice']);

        Permission::firstOrCreate(['name' => 'view case']);
        Permission::firstOrCreate(['name' => 'create case']);
        Permission::firstOrCreate(['name' => 'update case']);
        Permission::firstOrCreate(['name' => 'delete case']);

        Permission::firstOrCreate(['name' => 'view billing']);
        Permission::firstOrCreate(['name' => 'create billing']);
        Permission::firstOrCreate(['name' => 'update billing']);
        Permission::firstOrCreate(['name' => 'delete billing']);

        Permission::firstOrCreate(['name' => 'view payment']);
        Permission::firstOrCreate(['name' => 'create payment']);
        Permission::firstOrCreate(['name' => 'update payment']);
        Permission::firstOrCreate(['name' => 'delete payment']);

        Permission::firstOrCreate(['name' => 'view customer']);
        Permission::firstOrCreate(['name' => 'create customer']);
        Permission::firstOrCreate(['name' => 'update customer']);
        Permission::firstOrCreate(['name' => 'delete customer']);

        // Create Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $adminRole      = Role::firstOrCreate(['name' => 'admin']);
        $userRole       = Role::firstOrCreate(['name' => 'user']);

        // give all permissions to super-admin role.
        $allPermissionNames = Permission::pluck('name')->toArray();

        $superAdminRole->givePermissionTo($allPermissionNames);

        // give permissions to admin role.
        $adminRole->givePermissionTo(['view role']);
        $adminRole->givePermissionTo(['view permission']);
        $adminRole->givePermissionTo(['create user', 'view user', 'update user']);
        $adminRole->givePermissionTo(['view customer', 'create customer', 'update customer']);


        // Create User and assign Role to it.

        $superAdminUser = User::firstOrCreate([
                    'email' => 'admin@gmail.com',
                ], [
                    'name' => 'Super Admin',
                    'email' => 'admin@gmail.com',
                    'username' => 'superadmin',
                    'password' => Hash::make ('12345678'),
                    'email_verified_at' => now(),
                ]);

        $superAdminUser->assignRole($superAdminRole);

        $superAdminProfile = $superAdminUser->profile()->firstOrCreate([
            'user_id' => $superAdminUser->id,
        ], [
            'user_id' => $superAdminUser->id,
            'first_name' => $superAdminUser->name,
        ]);
    }
}
