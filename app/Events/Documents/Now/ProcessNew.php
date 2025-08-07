<?php

namespace App\Events\Documents\Now;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;

class ProcessNew implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    /** @var array */
    public $payload;
    /** @var string */
    public $sessionId;
    /** @var string */
    public $resumableId;
    
    
    /**
     * Create a new event instance.
     * @property string $message
     * @property string $sessionId
     * @property string $resumableId
     */
    public function __construct($payload, $sessionId, $resumableId)
    {
        $this->payload = $payload;
        $this->sessionId = $sessionId;
        $this->resumableId = $resumableId;
        Log::channel('user_log')
            ->info('Construct broadcast process new', [
                'payload' => $this->payload,
                'session' => $this->sessionId,
                'resumable' => $this->resumableId,
            ]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        Log::channel('user_log')->info('Broadcast on process new', ['sessionId' => $this->sessionId]);
        return [
            new PrivateChannel('now-process_new_docs'),
        ];
    }
    
    
    public function broadcastWith() {
        Log::channel('user_log')
            ->info('Broadcast with process process new', [
                'payload' => $this->payload,
                'session' => $this->sessionId,
                'resumable' => $this->resumableId,
            ]);
        return [ 
            'payload' => $this->payload,
            'resumable' => $this->resumableId,
        ];
    }
    
    
    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'Now_ProcessNewDocs';
    }
    
}
