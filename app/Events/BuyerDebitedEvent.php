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

class BuyerDebitedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $backlink;
    public $totalAmount;
    public $backlinkIds;

    /**
     * Create a new event instance.
     *
     * @param Backlink $backlink
     * @param          $totalAmount
     * @param          $backlinkIds
     */
    public function __construct(Backlink $backlink, $totalAmount, $backlinkIds)
    {
        $this->backlink = $backlink;
        $this->totalAmount = $totalAmount;
        $this->backlinkIds = $backlinkIds;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->backlink->user->id);
    }

    public function broadcastAs()
    {
        return 'user.notify';
    }
}
