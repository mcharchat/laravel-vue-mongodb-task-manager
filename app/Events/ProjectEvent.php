<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type;
    public $channel;
    public $project;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($channel, $project, $type)
    {
        $this->type = $type;
        $this->channel = 'squad.' . $channel;
        $this->project = $project;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\PrivateChannel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel($this->channel);
    }

    public function broadcastAs()
    {
        return 'project-event';
    }
}
