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

class StatusUpload implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    /** @var string */
    public $message;
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
    public function __construct($message, $sessionId, $resumableId)
    {
        $this->message = $message;
        $this->sessionId = $sessionId;
        $this->resumableId = $resumableId;
        Log::channel('user_log')
            ->info('Construct broadcast status upload', [
                'message' => $this->message,
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
        Log::channel('user_log')->info('Broadcast on status upload', ['sessionId' => $this->sessionId]);
        return [
            new PrivateChannel('now-status_upload.' . $this->sessionId),
        ];
    }
    
    
    public function broadcastWith() {
        Log::channel('user_log')
            ->info('Broadcast with process status upload', [
                'message' => $this->message,
                'session' => $this->sessionId,
                'resumable' => $this->resumableId,
            ]);
        return [ 
            'message' => $this->message,
            'resumable' => $this->resumableId,
        ];
    }
    
    
    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'Now_ProcessStatusUpload';
    }
    
}
