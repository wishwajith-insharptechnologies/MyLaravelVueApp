<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // Reset cached roles and permissions
                app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

                // Create permissions
                Permission::create(['name' => 'edit articles']);
                Permission::create(['name' => 'delete articles']);
                Permission::create(['name' => 'publish articles']);
                Permission::create(['name' => 'unpublish articles']);

                // Create roles and assign existing permissions
                $role1 = Role::create(['name' => 'writer']);
                $role1->givePermissionTo('edit articles');
                $role1->givePermissionTo('delete articles');

                $role2 = Role::create(['name' => 'admin']);
                $role2->givePermissionTo(Permission::all());

                // Create sample users and assign roles
                $user1 = User::create([
                    'name' => 'Writer User',
                    'email' => 'writer@example.com',
                    'password' => bcrypt('password')
                ]);
                $user1->assignRole($role1);

                $user2 = User::create([
                    'name' => 'Admin User',
                    'email' => 'admin@example.com',
                    'password' => bcrypt('password')
                ]);
                $user2->assignRole($role2);
    }
}
