<?php

namespace App\Repository;

use App\Models\User;

class UserRepository
{
    public static function get($id)
    {
        return User::find($id);
    }

    public static function getAllWithRoles()
    {
        return User::with('roles')->orderBy('created_at', 'desc')->get();
    }

    public static function store($userData)
    {
        return User::create($userData);
    }
}
