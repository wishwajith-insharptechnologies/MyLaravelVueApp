<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Responses\ApiResponse;
use App\Repository\RoleRepository;
use Spatie\Permission\Models\Role;
use App\Http\Resources\Users\RoleResource;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    public function index()
    {
        try {
            $roles = RoleRepository::getAllRoles();
            return ApiResponse::success(RoleResource::collection($roles), 'Roles retrieved successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|unique:roles,name|max:255',
                'description' => 'nullable',
            ]);

            $role = RoleRepository::createRole($validatedData);
            return ApiResponse::success(new RoleResource($role), 'Role created successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function show($id)
    {
        try {
            $role = RoleRepository::getRoleById($id);
            if ($role) {
                return ApiResponse::success(new RoleResource($role), 'Role retrieved successfully');
            }
            return ApiResponse::error('Role not found', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|max:255|unique:roles,name,' . $id,
                'description' => 'nullable'
            ]);

            $role = RoleRepository::updateRole($validatedData, $id);
            if ($role) {
                return ApiResponse::success(new RoleResource($role), 'Role updated successfully');
            }
            return ApiResponse::error('Role not found', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $userCount = User::role($role->name)->count();
            if($userCount > 0){
                return ApiResponse::error('Role is assigned to one or more users and cannot be deleted.', Response::HTTP_BAD_REQUEST);
            }

            $deleted = RoleRepository::deleteRole($id);
            if ($deleted) {
                return ApiResponse::success(null, 'Role deleted successfully');
            }
            return ApiResponse::error('Role not found', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
