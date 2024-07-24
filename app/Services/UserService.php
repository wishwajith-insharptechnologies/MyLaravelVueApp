<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Mail\WelcomeEmail;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Jobs\sendUserCreateEmailNotification;

class UserService
{

    public static function getUsers()
    {
        return UserRepository::getAllWithRoles();
    }

    public static function createUser($request)
    {
        $email_verified_at = false;
        // if ($request['email_verified_at']) {
        //     $email_verified_at = now();
        // }

        $password = CommonService::generateStrongPassword(8);

        $userData = [
            'name'              => $request['name'],
            'email'             => $request['email'],
            'email_verified_at' => $email_verified_at,
            'password'          => Hash::make($password),
        ];

        $user = UserRepository::store($userData);

        if ($request['role']) {
            $role = Role::find($request['role']);
            $user->assignRole($role->name);
        }

        sendUserCreateEmailNotification::dispatch($user, $password);

        return $user;
    }

    public static function updateUser($request)
    {
        $user = UserRepository::get($request['id']);

        if (!$user) {
            throw new \Exception('User not found.');
        }

        $userData = [
            'name' => $request['name'],
            'email' => $request['email'],
            'email_verified_at' => $request['email_verified_at'] ? now() : $user->email_verified_at,
        ];

        $user->update($userData);

        if (isset($request['role']) && $request['role']) {
            $user->roles()->detach();

            $role = Role::find($request['role']);
            if ($role) {
                $user->assignRole($role->name);
            } else {
                throw new \Exception('Role not found.');
            }
        }

        return $user;
    }

}
