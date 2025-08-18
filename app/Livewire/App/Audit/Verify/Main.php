<?php

namespace App\Livewire\App\Audit\Verify;

use Livewire\Component;
use Livewire\Attributes;

use App\Utils\ModelUtils;
use Illuminate\Support\Facades\Auth;

class Main extends Component
{
    public $can_access;
    public bool $is_found = false;
    public $id_document;
    public $idenfier;
    
    
    public function mount($identifier) {
        $find = ModelUtils::createInstanceModel(\App\Models\Documents\SignedQR::class)
            ->where('identifier', '=', $identifier)
            ->first();
        
        if (! $find) {
            return;
        }
        
        $signed = ModelUtils::createInstanceModel(\App\Models\Documents\Signed::class)
            ->find($find->id_document_signed);
        
        if (! $signed) {
            return;
        }
        
        $this->is_found = true;
        $this->id_document = $signed->id_document;
        $this->idenfier = $identifier;
    }
    
    
    
    
    #[Attributes\Layout('livewire.layout.audit.template')]
    public function render()
    {
        return view('livewire.app.audit.verify.main');
    }
}
