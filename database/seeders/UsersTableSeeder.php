<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminRole = Role::whereName('Super Admin')->first();
        $adminRole = Role::whereName('Admin')->first();
        $moderatorRole = Role::whereName('Moderator')->first();
        $editorRole = Role::whereName('Editor')->first();
        $userRole = Role::whereName('User')->first();

        if (config('users.seeders.superAdmin.enabled')) {
            $seededSuperAdmin = config('users.seeders.superAdmin.email');
            $user = User::where('email', '=', $seededSuperAdmin)->first();
            if ($user === null) {
                $user = User::updateOrCreate([
                    'name'                          => config('users.seeders.superAdmin.name'),
                    'email'                         => $seededSuperAdmin,
                    'password'                      => Hash::make(config('users.seeders.superAdmin.password')),
                    'email_verified_at'             => now(),
                    'theme_dark'                    => false,
                ]);
                $user->attachRole($superAdminRole);
            }
        }

        if (config('users.seeders.admin.enabled')) {
            $seededAdmin = config('users.seeders.admin.email');
            $user = User::where('email', '=', $seededAdmin)->first();
            if ($user === null) {
                $user = User::updateOrCreate([
                    'name'                          => config('users.seeders.admin.name'),
                    'email'                         => $seededAdmin,
                    'password'                      => Hash::make(config('users.seeders.admin.password')),
                    'email_verified_at'             => now(),
                    'theme_dark'                    => false,
                ]);
                $user->attachRole($adminRole);
            }
        }

        if (config('users.seeders.user.enabled')) {
            $seededUser = config('users.seeders.user.email');
            $user = User::where('email', '=', $seededUser)->first();
            if ($user === null) {
                $user = User::updateOrCreate([
                    'name'                          => config('users.seeders.user.name'),
                    'email'                         => $seededUser,
                    'password'                      => Hash::make(config('users.seeders.user.password')),
                    'email_verified_at'             => now(),
                    'theme_dark'                    => false,
                ]);
                $user->attachRole($userRole);
            }
        }
    }
}
