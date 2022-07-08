<?php

namespace App\Events;

use App\Models\WriterExam;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WriterExamProcessedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $writerExam, $mode, $reason;

    /**
     * Create a new event instance.
     *
     * @param WriterExam $writerExam
     * @param $mode
     * @param $reason
     */
    public function __construct(WriterExam $writerExam, $mode, $reason = null)
    {
        $this->writerExam = $writerExam;
        $this->mode = $mode;
        $this->reason = $reason;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('writer-exam.' . $this->writerExam->id);
    }

    public function broadcastAs()
    {
        return 'writer-exam.notify';
    }
}
