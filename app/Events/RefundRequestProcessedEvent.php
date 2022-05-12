<?php

namespace App\Events;

use App\Models\WalletTransaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RefundRequestProcessedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $walletTransaction, $mode;

    /**
     * Create a new event instance.
     *
     * @param WalletTransaction $walletTransaction
     * @param $mode
     */
    public function __construct(WalletTransaction $walletTransaction, $mode)
    {
        $this->walletTransaction = $walletTransaction;
        $this->mode = $mode;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('wallet-transaction.' . $this->walletTransaction->id);
    }

    public function broadcastAs()
    {
        return 'wallet-transaction.notify';
    }
}
