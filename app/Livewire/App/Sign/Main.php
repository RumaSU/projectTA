<?php

namespace App\Livewire\App\Sign;

use Livewire\Component;
use Livewire\Attributes;

use App\Services\Documents\GetService;
use App\Services\SignServices;
use Illuminate\Support\Facades\Auth;

class Main extends Component
{
    
    public bool $is_found;
    public bool $is_uncategorized;
    public string $id_document;
    
    public function mount(string $id_document) {
        // dump($id_document);
        
        $this->is_found = SignServices::can_access($id_document, Auth::user()->id_user);
        $this->id_document = $id_document;
    }
     
    #[Attributes\On('Not-Found-File')]
    public function not_found_file($data) {
        if (! is_array($data) ||
            ! ($data && array_key_exists('status', $data))) {
            return;
        }
        
        $this->is_found = false;
    }
    
    #[Attributes\On('Document-Type-Uncategorized')]
    public function document_uncategorized($data) {
        if (! is_array($data) ||
            ! ($data && array_key_exists('status', $data))) {
            return;
        }
        
        $this->is_uncategorized = true;
    }
    
    
    
    
    #[Attributes\Layout('livewire.layout.sign.template')]
    public function render()
    {
        return view('livewire.app.sign.main');
    }
}
