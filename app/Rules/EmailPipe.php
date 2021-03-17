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

    public $mod, $id, $value;

    public function __construct($mod, $id = null)
    {
        $this->mod = $mod;
        $this->id = $id;
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
        $result = $this->mod === 'add'
            ? ExtDomain::whereRaw("find_in_set('$value',replace(email,'|',','))")
            : ExtDomain::whereRaw("find_in_set('$value',replace(email,'|',','))")->where('id','!=', $this->id);

        return $result->count() === 0;
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
