<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePackageRequest extends FormRequest
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
            'description'   => 'required|string',
            'title'         => 'required|string',
            'rank'          => 'required|integer',
            'validity'      => 'required',
            'price'         => 'required|numeric',
            'discount'      => 'required|numeric',
            'images'        => 'nullable',
            'status'        => 'required', // Change to your valid statuses
            'trial_period'  => 'required|integer',
            // 'product_id'    => 'required|integer|exists:products,id',
            // 'category_id'   => 'required|integer|exists:categories,id',
            // 'limitation_id' => 'required|integer|exists:limitations,id',
        ];
    }
}
