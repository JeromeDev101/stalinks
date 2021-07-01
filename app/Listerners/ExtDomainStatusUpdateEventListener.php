<?php

namespace App\Listerners;

use App\Events\ExtDomainStatusUpdateEvent;
use App\Services\ExtDomainService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExtDomainStatusUpdateEventListener
{
    protected $extDomainService;

    /**
     * Create the event listener.
     * @param ExtDomainService $extDomainService
     * @return void
     */
    public function __construct(ExtDomainService $extDomainService)
    {
        $this->extDomainService = $extDomainService;
    }

    /**
     * Handle the event.
     *
     * @param  ExtDomainStatusUpdateEvent  $event
     * @return void
     */
    public function handle(ExtDomainStatusUpdateEvent $event)
    {
        $this->extDomainService->updateStatusDate($event->obj);
    }
}
