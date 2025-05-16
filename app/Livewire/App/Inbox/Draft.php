<?php

namespace App\Livewire\App\Inbox;

use Livewire\Attributes;
use Livewire\Component;

class Draft extends Component
{
    #[Attributes\Layout('livewire.layout.dashboard.inbox.main')]
    public function render()
    {
        return view('livewire.app.inbox.draft');
    }
}
