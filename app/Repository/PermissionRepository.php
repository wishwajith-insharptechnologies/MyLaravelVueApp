<?php

namespace App\Repositories;

use Spatie\Permission\Models\Permission;

class PermissionRepository
{
    public static function getAllPermissions()
    {
        return Permission::all();
    }

    public static function createPermission($data)
    {
        return Permission::create($data);
    }

    public static function getPermissionById($id)
    {
        return Permission::findOrFail($id);
    }

    public static function updatePermission($data, $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->update($data);
        return $permission;
    }

    public static function deletePermission($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
    }
}
