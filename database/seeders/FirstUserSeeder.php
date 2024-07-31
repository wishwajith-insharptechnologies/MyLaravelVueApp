<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FirstUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 20,
            'name' => 'super admin',
            'last_name' => 'last name',
            'email' => 'superadmin@superadmin.com',
            'email_verified_at' => null,
            'password' =>  Hash::make(12345678),
            'remember_token' => null,
        ]);
    }
}
