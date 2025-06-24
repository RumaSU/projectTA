<?php

namespace App\Livewire\Layout\Partial;

use App\Library\SessionHelper;

use Livewire\Attributes;
use Livewire\Component;

class SetTimezone extends Component
{
    #[Attributes\On('Set-New-Timezone')]
    public function setSessionTimezone($dataDispatch) {
        $localData = json_decode(json_encode($dataDispatch));
        
        SessionHelper::putSession('timezone', $localData->timezone);
    }
    
    public function render()
    {
        return view('livewire.layout.partial.set-timezone');
    }
}
