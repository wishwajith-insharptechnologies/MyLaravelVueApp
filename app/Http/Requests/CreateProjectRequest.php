<?php

namespace App\Http\Requests;

use App\Rules\NotEmptyArray;
use Illuminate\Foundation\Http\FormRequest;

class CreateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'projectName' => 'required|string|max:255',
            'projectType' => 'required',
            'limitation' =>  'required',
            'environmentType' => 'required',
            'description' => 'nullable|string',
            'image' => 'nullable',
            'link' => 'nullable|url',
            'secretCode' => 'string|unique:projects,secret_code|max:255',
        ];
    }

        /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            'limitation' => 'Import Project Limitations',
        ];
    }
}
