<?php

namespace App\Events;

use App\Models\LinkInjection;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LinkInjectionRequestEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $injection, $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(LinkInjection $injection, User $user)
    {
        $this->injection = $injection;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('link-injection' . $this->injection->id);
    }

    public function broadcastAs()
    {
        return 'link-injection.notify';
    }
}
