<?php

namespace App\Livewire\Layout\Partial;

use Livewire\Component;

class RefreshCSRF extends Component
{
    public function render()
    {
        return view('livewire.layout.partial.refresh-c-s-r-f');
    }
    
    public function refreshCSRF() {
        return response()->json(['csrf_token' => csrf_token()]);
    }
}
