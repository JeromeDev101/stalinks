<?php

namespace App\Events;

use App\Models\Article;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ArticleCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $article;

    /**
     * Create a new event instance.
     *
     * @param Article $article
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('article' . $this->article->id);
    }

    public function broadcastAs()
    {
        return 'user.notify';
    }
}
