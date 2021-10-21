<?php

namespace App\Events;

use App\Models\Backlink;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BacklinkLiveSellerEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $seller;
    public $backlink;

    /**
     * Create a new event instance.
     *
     * @param Backlink $backlink
     * @param User $seller
     */
    public function __construct(Backlink $backlink, User $seller)
    {
        $this->backlink = $backlink;
        $this->seller = $seller;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->seller->id);
    }

    public function broadcastAs()
    {
        return 'user.notify';
    }
}
