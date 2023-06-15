<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrivateTaskEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type;
    public $participants_ids;
    public $task;
    public $channels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($participants_ids, $task, $type)
    {
        $this->type = $type;
        $this->participants_ids = $participants_ids;
        $this->channels = $participants_ids->map(function ($participantId) {
            return new PrivateChannel('user.' . $participantId);
        })->toArray();
        $this->task = $task;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\PrivateChannel|array
     */
    public function broadcastOn()
    {
        // broadcast to every private channel in the $this->channels array
        return $this->channels;
    }

    public function broadcastAs()
    {
        return 'task-event';
    }
}
