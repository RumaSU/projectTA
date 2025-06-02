<?php

namespace App\Livewire\App\Mail;

use Livewire\Attributes;
use Livewire\Component;

use Flasher\Laravel\Facade\Flasher;

class Data extends Component
{
    public $typeMail;
    public $searchMail;
    public $randMail;
    
    public function mount() {
        $this->typeMail = request()->get('t') ?? 'inbox';
        $this->searchMail = request()->get('s') ?? '';
        $this->randMail = rand(10, 25);
    }
    
    #[Attributes\On('Mail-SPA-Page')]
    public function mailSPA($dataDispatch) {
        $localData = (object)$dataDispatch;
        // dump($dataDispatch);
        $this->typeMail = $localData->page;
        // Flasher::success('Dispatch success');
        // flash()->success('Dispatch success');
    }
    
    #[Attributes\On('Mail-Search-Page')]
    public function mailSearch($dataDispatch) {
        $localData = (object)$dataDispatch;
        
        // dump($dataDispatch);
        $this->searchMail = $localData->search;
        
        // $this->typeMail = $localData->page;
        // Flasher::success('Dispatch success');
        // flash()->success('Dispatch success');
    }
    
    public function render()
    {
        return view('livewire.app.mail.data');
    }
}
