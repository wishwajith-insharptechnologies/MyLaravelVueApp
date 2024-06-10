<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Role Types
         *
         */
        $roles = [
            [
                'name'        => 'Super Admin',
                'slug'        => 'super.admin',
                'description' => 'Super Admin Role',
                'level'       => 5,
            ],
            [
                'name'        => 'Admin',
                'slug'        => 'admin',
                'description' => 'Admin Role',
                'level'       => 4,
            ],
            [
                'name'        => 'Moderator',
                'slug'        => 'moderator',
                'description' => 'Moderator Role',
                'level'       => 3,
            ],
            [
                'name'        => 'Editor',
                'slug'        => 'editor',
                'description' => 'Editor Role',
                'level'       => 2,
            ],
            [
                'name'        => 'User',
                'slug'        => 'user',
                'description' => 'User Role',
                'level'       => 1,
            ],
            [
                'name'        => 'Unverified',
                'slug'        => 'unverified',
                'description' => 'Unverified Role',
                'level'       => 0,
            ],
        ];

        /*
         * Add Role Items
         *
         */
        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                ['slug' => $role['slug'], 'description' => $role['description'], 'level' => $role['level']]
            );
        }
    }
}
