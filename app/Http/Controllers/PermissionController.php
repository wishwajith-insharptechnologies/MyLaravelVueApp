<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Repositories\PermissionRepository;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = PermissionRepository::getAllPermissions();
        return response()->json(['permissions' => $permissions]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:permissions,name|max:255',
        ]);

        $permission = PermissionRepository::createPermission($validatedData);
        return response()->json(['permission' => $permission]);
    }

    public function show($id)
    {
        $permission = PermissionRepository::getPermissionById($id);
        return response()->json(['permission' => $permission]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255|unique:permissions,name,' . $id,
        ]);

        $permission = PermissionRepository::updatePermission($validatedData, $id);
        return response()->json(['permission' => $permission]);
    }

    public function destroy($id)
    {
        PermissionRepository::deletePermission($id);
        return response()->json(['message' => 'Permission deleted successfully']);
    }
}
