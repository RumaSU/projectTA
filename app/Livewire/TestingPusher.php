<?php

namespace App\Livewire;

use Livewire\Attributes;
use Livewire\Component;

class TestingPusher extends Component
{
    public $message = '';

    public function getListeners()
    {
        return [
            "echo:Testing.KONTOL,TestingEventPusher" => 'handleIncomingMessage',
            "echo:kontol.KONTOL,TestingEventPusher" => 'handleIncomingMessage',
        ];
    }
    
    public function handleIncomingMessage($payload)
    {
        $this->message = 'memek';
        dump($payload);
    }
    
    #[Attributes\Layout('livewire.layout.dashboard.template')]
    public function render()
    {
        return view('livewire.testing-pusher');
    }
}
