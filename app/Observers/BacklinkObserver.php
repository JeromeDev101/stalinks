<?php

namespace App\Observers;

use App\BacklinkStatus;
use App\Models\Backlink;
use App\Repositories\Traits\BuyTrait;

class BacklinkObserver
{
    use BuyTrait;

    /**
     * Handle the backlink "created" event.
     *
     * @param  \App\Backlink  $backlink
     * @return void
     */
    public function created(Backlink $backlink)
    {
        BacklinkStatus::create([
            'backlink_id' => $backlink->id,
            'status' => $backlink->status
        ]);
    }

    /**
     * Handle the backlink "updated" event.
     *
     * @param  \App\Backlink  $backlink
     * @return void
     */
    public function updated(Backlink $backlink)
    {
        $editedFields = $backlink->getDirty();

        if (isset($editedFields['status'])) {
            BacklinkStatus::create([
                'backlink_id' => $backlink->id,
                'status' => $backlink->status
            ]);

            if ($backlink->getOriginal('status') == 'To Be Validated' && $editedFields['status'] == 'Processing') {
                $this->updateStatus('Purchased', $backlink->publisher_id);
            }
        }
    }

    /**
     * Handle the backlink "deleted" event.
     *
     * @param  \App\Backlink  $backlink
     * @return void
     */
    public function deleted(Backlink $backlink)
    {
        //
    }

    /**
     * Handle the backlink "restored" event.
     *
     * @param  \App\Backlink  $backlink
     * @return void
     */
    public function restored(Backlink $backlink)
    {
        //
    }

    /**
     * Handle the backlink "force deleted" event.
     *
     * @param  \App\Backlink  $backlink
     * @return void
     */
    public function forceDeleted(Backlink $backlink)
    {
        //
    }
}
