<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Responses\ApiResponse;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Users\RoleResource;
use App\Http\Resources\Users\UsersResource;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function user(Request $request)
    {

        if (auth('sanctum')->check()) {
            return response()->json(auth('sanctum')->user());
        }
    }

    public function authUser(Request $request)
    {
        try {
            $user = UserService::getAuthUser();
            return ApiResponse::success(new UsersResource($user), 'users retrieved successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // return response()->json(User::paginate($per));
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

    public function userPasswordUpdate(Request $request)
    {
        try {
            $user = $request->user();
            $request->merge([
                'current_password' => $request->input('currentPassword'),
                'new_password' => $request->input('newPassword'),
                'new_password_confirmation' => $request->input('confirmNewPassword'),
                ]);
            $validatedData = $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
            ]);
            if (!Hash::check($request->current_password, $user->password)) {
                return ApiResponse::error('The provided password does not match our records.', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $user = UserService::updateUserPassword($user, $validatedData);
            return ApiResponse::success($user, 'users retrieved successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
