<?php

namespace App\Rules;

use App\Models\UsersPaymentType;
use Illuminate\Contracts\Validation\Rule;

class PaymentInfoExists implements Rule
{
    protected $user_id;

    /**
     * Create a new rule instance.
     *
     * @param $user_id
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
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
        $count = UsersPaymentType::join('users', 'users.id', '=', 'users_payment_type.user_id')
            ->where('users_payment_type.account', $value)
            ->where('users.status', 'active')
            ->where('users_payment_type.user_id', '!=', $this->user_id)
            ->where('users_payment_type.deleted_at', null)->count();

        return $count === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Payment info :input is already taken by another user.';
    }
}
