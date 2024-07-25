<?php

namespace App\Repository;

use Spatie\Permission\Models\Role;

class RoleRepository
{
    public static function getAllRoles()
    {
        return Role::orderBy('created_at', 'desc')->get();
    }

    public static function createRole($data)
    {
        return Role::create($data);
    }

    public static function getRoleById($id)
    {
        return Role::findOrFail($id);
    }

    public static function updateRole($data, $id)
    {
        $role = Role::findOrFail($id);
        $role->update($data);
        return $role;
    }

    public static function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return $role;
    }
}
