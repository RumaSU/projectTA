<?php

namespace App\Livewire\Layout\Auth;

use Livewire\Component;
use Livewire\Attributes;

class Template extends Component {
    #[Attributes\Layout('layout.main')]
    public function render() {
        return view('livewire.layout.auth.template');
    }
}