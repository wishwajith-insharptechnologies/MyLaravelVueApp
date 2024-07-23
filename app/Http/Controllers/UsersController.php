<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Responses\ApiResponse;
use App\Repository\RoleRepository;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Users\RoleResource;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\Users\UsersResource;
use App\Repository\UserRepository;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function user(Request $request)
    {

        if (auth('sanctum')->check()) {
            return response()->json(auth('sanctum')->user());
        }
    }

    public function users(Request $request)
    {
        try {
            $users = UserService::getUsers();
            return ApiResponse::success(UsersResource::collection($users), 'users retrieved successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // return response()->json(User::paginate($per));
    }

    public function toggleVerify(Request $request)
    {
        $data = $request->all();
        $user = User::findOrFail($data['user']['id']);
        if ($data['action'] == 'unVerifyUser') {
            $user->email_verified_at = null;
        } else {
            $user->email_verified_at = now();
        }
        $user->save();
        $user->load('roles');

        return response()->json($user);
    }

    public function createUser(CreateUserRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = UserService::createUser($validated);
            return ApiResponse::success($user, 'users retrieved successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateUser(UpdateUserRequest $request, User $user)
    {
        try {

            $validated = $request->validated();

            $user = UserService::updateUser($request);
            return ApiResponse::success($user, 'users retrieved successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteUser(Request $request, User $user)
    {
        $user->delete();

        return response()->json($user);
    }
}
