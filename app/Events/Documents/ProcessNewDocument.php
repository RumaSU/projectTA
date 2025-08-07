<?php

namespace App\Events\Documents;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;

class ProcessNewDocument implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    
    public $message;
    public $data;
    public $sessionId;
    public $resumableId;
    
    /**
     * Create a new event instance.
     * @param string $message
     * @param array $data
     * @param string $sessionId
     * @param string $resumableId
     */
    public function __construct($message, $data = [], $sessionId, $resumableId)
    {
        $this->message = $message;
        $this->data = $data;
        $this->sessionId = $sessionId;
        $this->resumableId = $resumableId;
        Log::channel('user_log')
            ->info('Construct broadcast process new documents', [
                'message' => $this->message,
                'data' => $this->data,
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
        Log::channel('user_log')->info('Broadcast on process new documents', ['sessionId' => $this->sessionId]);
        return [
            // new Channel('process_docs.' . $this->sessionId),
            new PrivateChannel('process_docs.' . $this->sessionId),
        ];
    }
    
    
    public function broadcastWith() {
        Log::channel('user_log')
            ->info('Broadcast with process new documents', [
                'message' => $this->message,
                'data' => $this->data,
                'session' => $this->sessionId,
                'resumable' => $this->resumableId,
            ]);
        return [
            'message' => $this->message,
            'data' => $this->data,
            'session' => $this->sessionId,
            'resumable' => $this->resumableId,
        ];
    }
     
    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'ProcessNewDocument';
    }
    
}
