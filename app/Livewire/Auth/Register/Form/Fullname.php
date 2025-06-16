<?php

namespace App\Livewire\Auth\Register\Form;

use Livewire\Attributes;
use Livewire\Component;

class Fullname extends Component
{
    #[Attributes\Layout('livewire.auth.register.main')]
    public function render()
    {
        return view('livewire.auth.register.form.fullname');
    }
}
