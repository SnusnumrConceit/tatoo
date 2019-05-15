<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WriteAudit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $subject, $user_id, $type, $status;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($subject, $type, $status)
    {
        $this->subject = $subject;
        $this->type = $type;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
