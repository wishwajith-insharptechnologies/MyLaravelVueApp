<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|min:3|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6|max:255|confirmed',
        ]);

        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
        ]);

        if ($user) {
            // $userRole = Role::whereName('User')->first();
            // $user->attachRole($userRole);
            // event(new Registered($user));
            // // Log the user in
            // // Auth::login($user);
            $token = $user->createToken('access_token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'type'  => 'Bearer',
                'user'  => $user,
                'status'  => 200,
            ]);
        }
    }
}
