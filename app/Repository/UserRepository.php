<?php

namespace App\Repository;

use App\Models\User;

class UserRepository
{
    public static function get($id)
    {
        return User::find($id);
    }
}
