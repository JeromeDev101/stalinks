<?php

namespace App\Rules;

use App\Models\ExtDomain;
use Illuminate\Contracts\Validation\Rule;

class EmailPipe implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $value;

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
        $this->value = $value;
        $result = ExtDomain::whereRaw("find_in_set('$value',replace(email,'|',','))")->count();

        return $result ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->value . ' is already taken.';
    }
}
