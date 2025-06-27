<?php

namespace App\Livewire\Auth\Register;

use App\Library\SessionHelper;

use Livewire\Component;
use Livewire\Attributes;


class Main extends Component
{
    
    public function mount() {
        SessionHelper::forgetSession('register_step');
        SessionHelper::forgetSession('register_step_initialized');
        
        $this->redirectRoute('auth.register.step.basic_info', navigate: true);
    }
    
    // #[Attributes\Layout('livewire.layout.auth.template')]
    // public function render()
    // {
    //     return view('livewire.auth.register.main');
    // }
}
