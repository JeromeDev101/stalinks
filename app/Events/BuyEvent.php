<?php

namespace App\Events;

use App\Models\Backlink;
use App\Models\Notif;
use App\Models\User;
use App\Repositories\NotificationRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BuyEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $backlink;

    public $user;

    /**
     * Create a new event instance.
     *
     * @param Backlink $backlink
     * @param User     $user
     */
    public function __construct(Backlink $backlink, User $user)
    {
        $this->user = $user;
        $this->backlink = $backlink;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->user->id);
    }

    public function broadcastAs()
    {
        return 'user.notify';
    }
}
