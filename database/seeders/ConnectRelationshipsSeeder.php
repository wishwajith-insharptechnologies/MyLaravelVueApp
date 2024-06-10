<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ConnectRelationshipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Get Available Permissions.
         */
        $permissions = Permission::all();

        /**
         * Attach Permissions to Roles.
         */
        $roleAdmin = Role::where('name', '=', 'Admin')->first();
        if ($roleAdmin) {
            foreach ($permissions as $permission) {
                $roleAdmin->givePermissionTo($permission);
            }
        }
    }
}
