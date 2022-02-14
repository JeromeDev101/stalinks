<?php

namespace App\Events;

use App\Models\Backlink;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SellerConfirmedPendingOrderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $backlink, $confirmation;

    /**
     * Create a new event instance.
     *
     * @param Backlink $backlink
     * @param $confirmation
     */
    public function __construct(Backlink $backlink, $confirmation)
    {
        $this->backlink = $backlink;
        $this->confirmation = $confirmation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('backlink.' . $this->backlink->id);
    }

    public function broadcastAs()
    {
        return 'backlink.notify';
    }
}
