<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Repositories\RoleRepository;

class RoleController extends Controller
{
    public function index()
    {
        $roles = RoleRepository::getAllRoles();
        return response()->json(['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:roles,name|max:255',
        ]);

        $role = RoleRepository::createRole($validatedData);
        return response()->json(['role' => $role]);
    }

    public function show($id)
    {
        $role = RoleRepository::getRoleById($id);
        return response()->json(['role' => $role]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255|unique:roles,name,' . $id,
        ]);

        $role = RoleRepository::updateRole($validatedData, $id);
        return response()->json(['role' => $role]);
    }

    public function destroy($id)
    {
        RoleRepository::deleteRole($id);
        return response()->json(['message' => 'Role deleted successfully']);
    }
}
