<?php

namespace App\Http\Requests\Users;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        // return $this->user()->hasOneRole(config('roles.models.role')::whereName('Super Admin')->first('id')->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $userId = $this->route('id');
        $user = User::find($userId);

        return [
            'name'              => 'required|min:3|max:255',
            'email'             => [
                'required',
                'email',
                function ($attribute, $value, $fail) use ($userId, $user) {
                    if ($user && $user->email !== $value) {
                        $exists = User::where('email', $value)->where('id', '<>', $userId)->exists();
                        if ($exists) {
                            $fail('The email has already been taken.');
                        }
                    }
                },
            ],
            // 'password'          => 'nullable|min:6|max:255|confirmed',
            'role'             => 'required',
            'theme_dark'        => 'nullable|boolean',
            'email_verified_at' => 'nullable',
        ];
    }
}
