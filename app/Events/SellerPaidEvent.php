<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SellerPaidEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $totalAmount, $backlinkIds;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param      $totalAmount
     * @param      $backlinkIds
     */
    public function __construct(User $user, $totalAmount, $backlinkIds)
    {
        $this->user = $user;
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
        return new PrivateChannel('user.' . $this->user->id);
    }

    public function broadcastAs()
    {
        return 'user.notify';
    }
}