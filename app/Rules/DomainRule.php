<?php

namespace App\Rules;

use App\Models\DomainProvider;
use Illuminate\Contracts\Validation\Rule;

class DomainRule implements Rule
{

    private $username;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($username)
    {
        $this->username = $username;
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
       $name = strtolower($value);
       $usernameDomain = strtolower($this->username);
       if (DomainProvider::where($attribute, $name)->where('username', $usernameDomain)->count() > 0) {
           return false;
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
        return 'The username and name already exist';
    }
}
