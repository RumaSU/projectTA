<?php

namespace App\Livewire\App\Dashboard\Main;

use Livewire\Component;
use Livewire\Attributes;

class Main extends Component
{
    #[Attributes\Layout('livewire.layout.dashboard.template')]
    public function render()
    {
        return view('livewire.app.dashboard.main.main');
    }
}
