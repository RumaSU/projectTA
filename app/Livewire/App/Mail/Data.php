<?php

namespace App\Livewire\App\Mail;

use Livewire\Attributes;
use Livewire\Component;

use Flasher\Laravel\Facade\Flasher;

class Data extends Component
{
    public $typeMail;
    
    public function mount() {
        $this->typeMail = request()->get('t') ?? 'inbox';
    }
    
    #[Attributes\On('Mail-SPA-Page')]
    public function mailSPA($dataDispatch) {
        $localData = (object)$dataDispatch;
        
        $this->typeMail = $localData->page;
        // Flasher::success('Dispatch success');
        flash()->success('Dispatch success');
    }
    
    public function render()
    {
        return view('livewire.app.mail.data');
    }
}
