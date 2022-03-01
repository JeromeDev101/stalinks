<?php

namespace App\Events;

use App\Models\Billing;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BillingReuploadDocEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $billing, $receipt;

    /**
     * Create a new event instance.
     *
     * @param Billing $billing
     * @param $receipt
     */
    public function __construct(Billing $billing, $receipt)
    {
        $this->billing = $billing;
        $this->receipt = $receipt;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('billing.' . $this->billing->id);
    }

    public function broadcastAs()
    {
        return 'billing.notify';
    }
}
