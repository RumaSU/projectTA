<?php

namespace App\Livewire\Auth\Login;

use Livewire\Component;
use Livewire\Attributes;

class Main extends Component
{
    
    #[Attributes\Layout('livewire.layout.auth.template')]
    public function render()
    {
        return view('livewire.auth.login.main');
    }
}
