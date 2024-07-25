<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NotEmptyArray implements Rule
{
    public function passes($attribute, $value)
    {
        return is_array($value) && !empty($value);
    }

    public function message()
    {
        return 'The project limitations is required';
    }
}

