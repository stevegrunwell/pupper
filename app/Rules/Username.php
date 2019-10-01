<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Username implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * Usernames may only consist of alphanumeric characters and underscores.
     *
     * Additionally, they may not begin with digits.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('/^[A-Z_][A-Z0-9_]+$/i', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.username');
    }
}
