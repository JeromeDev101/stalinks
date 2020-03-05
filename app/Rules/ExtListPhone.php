<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExtListPhone implements Rule
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
        $data = explode('|', $value);
        foreach($data as $item) {
            if (!is_string($item)) return false;
            $length = strlen($item);

            for ($i = 0; $i < $length; $i++) {
                if ($item[$i] > '9' || $item[$i] < '0') return false;
            }
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
        return 'The :attribute must be valid phone list split by "|" character.';
    }
}
