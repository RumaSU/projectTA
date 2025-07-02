<?php

namespace App\Livewire\App\Signature;

use Livewire\Attributes;
use Livewire\Component;

use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class Main extends Component
{
    
     
     

    #[Attributes\Layout('livewire.layout.dashboard.template')]
    public function render()
    {
        return view('livewire.app.signature.main');
    }
}
