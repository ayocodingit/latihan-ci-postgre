<?php

namespace App\Events;

use App\Models\Sampel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GenerateLetterSampleEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $sampel;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Sampel $sampel)
    {
        $this->sampel = $sampel;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-generate-letter-sample');
    }
}
