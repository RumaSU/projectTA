<?php

namespace App\Livewire\App\Mail;

use Livewire\Attributes;
use Livewire\Component;

use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class Main extends Component
{
    // public $spa_mail = array_map(fn($page) => (array)[
    //     'page' => $page,
    //     'default' => $page === 'inbox',
    //     'code' => Str::random(6),
    // ], ['inbox', 'sent', 'draft', 'all']);
    
    // public $spa_mail = array_map(function($page) {
    //     return [
    //         'page' => $page,
    //         'default' => $page === 'inbox',
    //         'code' => Str::random(6),
    //     ];
    // }, ['inbox', 'sent', 'draft', 'all']);
    
    // public $testVari = Str::random();
    private $listPage = ['inbox', 'sent', 'draft', 'all'];
    
    #[Attributes\Url]
    public $search;
    
    public $pageActive;
    #[Attributes\On('MailPageSpa')]
    public function MailPageSpa($dataPage) {
        dump($dataPage);
        // $this->search = "Test";
        // $this->js("alert('anjg')");
    }
    
    
    #[Attributes\Layout('livewire.layout.dashboard.template')]
    public function render()
    {
        return view('livewire.app.mail.main');
    }
}
