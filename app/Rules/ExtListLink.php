<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class ExtListLink implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

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
        $data = explode('|', $value);
        foreach($data as $item) {
            if (filter_var( $item, FILTER_VALIDATE_URL) === false) return false;
            if (Str::contains($item, 'facebook.com') === false  && Str::contains($item, 'fb.com') === false) return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be valid facebook url list split by "|" character.';
    }
}
