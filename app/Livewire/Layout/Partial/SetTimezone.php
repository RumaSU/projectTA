<?php

namespace App\Livewire\Layout\Partial;

use App\Library\SessionHelper;

use Illuminate\Support\Facades\Cookie;

use Livewire\Attributes;
use Livewire\Component;

class SetTimezone extends Component
{
    #[Attributes\On('Set-New-Timezone')]
    public function setSessionTimezone($dataDispatch) {
        $localData = json_decode(json_encode($dataDispatch));
        
        Cookie::queue('timezone', $localData->timezone, env('SESSION_LIFETIME'));
        SessionHelper::putSession('timezone', $localData->timezone);
    }
    
    public function render()
    {
        return view('livewire.layout.partial.set-timezone');
    }
}
