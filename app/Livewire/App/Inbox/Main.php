<?php

namespace App\Livewire\App\Inbox;

use Livewire\Attributes;
use Livewire\Component;

class Main extends Component
{
    #[Attributes\Layout('livewire.layout.dashboard.inbox')]
    public function render()
    {
        return view('livewire.app.inbox.main');
    }
}
