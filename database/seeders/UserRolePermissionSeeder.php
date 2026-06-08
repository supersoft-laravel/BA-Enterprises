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
        Permission::create(['name' => 'view role']);
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'update role']);
        Permission::create(['name' => 'delete role']);

        Permission::create(['name' => 'view permission']);
        Permission::create(['name' => 'create permission']);
        Permission::create(['name' => 'update permission']);
        Permission::create(['name' => 'delete permission']);

        Permission::create(['name' => 'view user']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'update user']);
        Permission::create(['name' => 'delete user']);

        Permission::create(['name' => 'view staff']);
        Permission::create(['name' => 'create staff']);
        Permission::create(['name' => 'update staff']);
        Permission::create(['name' => 'delete staff']);

        Permission::create(['name' => 'view archived user']);
        Permission::create(['name' => 'create archived user']);
        Permission::create(['name' => 'update archived user']);
        Permission::create(['name' => 'delete archived user']);

        Permission::create(['name' => 'view setting']);
        Permission::create(['name' => 'create setting']);
        Permission::create(['name' => 'update setting']);
        Permission::create(['name' => 'delete setting']);

        Permission::create(['name' => 'view transfer']);
        Permission::create(['name' => 'create transfer']);
        Permission::create(['name' => 'update transfer']);
        Permission::create(['name' => 'delete transfer']);

        Permission::create(['name' => 'view alteration']);
        Permission::create(['name' => 'create alteration']);
        Permission::create(['name' => 'update alteration']);
        Permission::create(['name' => 'delete alteration']);

        Permission::create(['name' => 'view tax']);
        Permission::create(['name' => 'create tax']);
        Permission::create(['name' => 'update tax']);
        Permission::create(['name' => 'delete tax']);

        Permission::create(['name' => 'view insurance']);
        Permission::create(['name' => 'create insurance']);
        Permission::create(['name' => 'update insurance']);
        Permission::create(['name' => 'delete insurance']);

        Permission::create(['name' => 'view permit']);
        Permission::create(['name' => 'create permit']);
        Permission::create(['name' => 'update permit']);
        Permission::create(['name' => 'delete permit']);

        Permission::create(['name' => 'view fitness']);
        Permission::create(['name' => 'create fitness']);
        Permission::create(['name' => 'update fitness']);
        Permission::create(['name' => 'delete fitness']);

        Permission::create(['name' => 'view invoice']);
        Permission::create(['name' => 'create invoice']);
        Permission::create(['name' => 'update invoice']);
        Permission::create(['name' => 'delete invoice']);

        Permission::create(['name' => 'view case']);
        Permission::create(['name' => 'create case']);
        Permission::create(['name' => 'update case']);
        Permission::create(['name' => 'delete case']);

        Permission::create(['name' => 'view billing']);
        Permission::create(['name' => 'create billing']);
        Permission::create(['name' => 'update billing']);
        Permission::create(['name' => 'delete billing']);

        Permission::create(['name' => 'view payment']);
        Permission::create(['name' => 'create payment']);
        Permission::create(['name' => 'update payment']);
        Permission::create(['name' => 'delete payment']);

        // Create Roles
        $superAdminRole = Role::create(['name' => 'super-admin']); //as super-admin
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // give all permissions to super-admin role.
        $allPermissionNames = Permission::pluck('name')->toArray();

        $superAdminRole->givePermissionTo($allPermissionNames);

        // give permissions to admin role.
        $adminRole->givePermissionTo(['view role']);
        $adminRole->givePermissionTo(['view permission']);
        $adminRole->givePermissionTo(['create user', 'view user', 'update user']);


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
