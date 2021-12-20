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

class TeamInChargeUpdatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $team_in_charge;
    public $user;
    public $multiple;

    /**
     * Create a new event instance.
     *
     * @param $team_in_charge
     * @param $user
     * @param $multiple
     */
    public function __construct($team_in_charge, $user, $multiple = null)
    {
        $this->team_in_charge = $team_in_charge;
        $this->user = $user;
        $this->multiple = $multiple;
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
