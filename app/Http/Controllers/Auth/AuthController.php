<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function Login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'exists:users'],
            'password' => ['required'],
        ],[
            'email.exists' => ' User not found. Please contact the admin for support.',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = Auth::user();

            $authWithUserRole = [
                ...$auth->toArray(),
                'role' => 'develop'
            ];

            return response()->json($authWithUserRole, 200);
        }

        throw ValidationException::withMessages([
                'password' => ['Incorrect Password.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json([
            'status_code' => '200',
            'message'     => 'logged out successfully',
        ]);
    }

    public function guard($guard = 'web')
    {
        return Auth::guard($guard);
    }
}
