<?php

namespace App\Observers;

use App\Models\Registration;
use App\Models\User;

class RegistrationObserver
{
    /**
     * Handle the registration "created" event.
     *
     * @param  \App\registration  $registration
     * @return void
     */
    public function created(registration $registration)
    {
        //
    }

    /**
     * Handle the registration "updated" event.
     *
     * @param Registration $registration
     * @return void
     */
    public function updated(Registration $registration)
    {
        $updatedFields = $registration->getDirty();

        $registrationData = [];

        if (isset($updatedFields['company_type'])) {
            $registrationData['company_type'] = $updatedFields['company_type'];
        }

        if (isset($updatedFields['company_name'])) {
            $registrationData['company_name'] = $updatedFields['company_name'];
        }

        if (isset($updatedFields['company_url'])) {
            $registrationData['company_url'] = $updatedFields['company_url'];
        }

        if (isset($updatedFields['is_show_price_basis'])) {
            $registrationData['is_show_price_basis'] = $updatedFields['is_show_price_basis'];
        }

        if (!empty($registrationData)) {
            $subBuyers = optional(User::where('email', $registration->email)->first())->sub_buyers;

            if (!empty($subBuyers)) {
                Registration::whereIn('id', $subBuyers->pluck('id')->toArray())->update($registrationData);
            }
        }
    }

    /**
     * Handle the registration "deleted" event.
     *
     * @param  \App\registration  $registration
     * @return void
     */
    public function deleted(registration $registration)
    {
        //
    }

    /**
     * Handle the registration "restored" event.
     *
     * @param  \App\registration  $registration
     * @return void
     */
    public function restored(registration $registration)
    {
        //
    }

    /**
     * Handle the registration "force deleted" event.
     *
     * @param  \App\registration  $registration
     * @return void
     */
    public function forceDeleted(registration $registration)
    {
        //
    }
}
