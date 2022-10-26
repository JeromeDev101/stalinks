<?php

namespace App\Observers;

use App\Models\ExtDomain;

class ExtDomainObserver
{
    /**
     * Handle the ext domain "created" event.
     *
     * @param ExtDomain $extDomain
     * @return void
     */
    public function created(ExtDomain $extDomain)
    {
        //
    }

    /**
     * Handle the ext domain "updated" event.
     *
     * @param ExtDomain $extDomain
     * @return void
     */
    public function updated(ExtDomain $extDomain)
    {
        $editedFields = $extDomain->getDirty();

        // update the 3rd party status (apacaff) automatically based on the status of url prospect
        if (isset($editedFields['status']) && $extDomain->prospect) {
            $prospect_statuses = [
                10 => 'To be contacted',
                20 => 'To be contacted',
                30 => 'To be contacted',
                110 => 'To be contacted',
                100 => 'Qualified',
                90 => 'Unqualified',
                50 => 'Contacted',
                120 => 'Contacted Via Form',
                70 => 'In-touched',
                60 => 'Refused',
                55 => 'No Answer',
            ];

            if (array_key_exists($editedFields['status'], $prospect_statuses)) {
                $extDomain->prospect()->update(['status' => $prospect_statuses[$editedFields['status']]]);
            }
        }
    }

    /**
     * Handle the ext domain "deleted" event.
     *
     * @param ExtDomain $extDomain
     * @return void
     */
    public function deleted(ExtDomain $extDomain)
    {
        //
    }

    /**
     * Handle the ext domain "restored" event.
     *
     * @param ExtDomain $extDomain
     * @return void
     */
    public function restored(ExtDomain $extDomain)
    {
        //
    }

    /**
     * Handle the ext domain "force deleted" event.
     *
     * @param ExtDomain $extDomain
     * @return void
     */
    public function forceDeleted(ExtDomain $extDomain)
    {
        //
    }
}
