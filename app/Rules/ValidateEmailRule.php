<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateEmailRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $firstOfMail = substr($value, 0, strripos($value, '@'));

        if (preg_match('/^[0-9A-Za-z.]+$/', $firstOfMail)) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('common.errors.validate_email');
    }
}
