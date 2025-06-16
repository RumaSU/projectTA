<?php

namespace App\Livewire\Auth\Register;

use Livewire\Component;
use Livewire\Attributes;


class Main extends Component
{
    #[Attributes\Layout('livewire.auth.layout.template')]
    public function render()
    {
        return view('livewire.auth.register.main');
    }
}
