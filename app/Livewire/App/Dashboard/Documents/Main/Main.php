<?php

namespace App\Livewire\App\Dashboard\Documents\Main;

use Livewire\Component;
use Livewire\Attributes;

class Main extends Component
{
    #[Attributes\Layout('livewire.app.layout.documents')]
    public function render()
    {
        return view('livewire.app.dashboard.documents.main.main');
    }
}
