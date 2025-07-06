<?php

namespace App\Livewire\App\Signature;

use App\Library\Signatures\Helper as SignatureHelper;
use App\Library\Signatures\ModelUtils as SignatureModelsUtils;
use App\Library\Helper as LibHelper;

use App\Models\Signatures;

use Illuminate\Support\Facades\Auth;

use Livewire\Attributes;
use Livewire\Component;

class Main extends Component
{
    
    
    public $total_signatures;

    public function mount() {
        
        $this->total_signatures = SignatureModelsUtils::countSignatures();
        
    }
    
    
     
     
    
    #[Attributes\Layout('livewire.layout.dashboard.template')]
    public function render()
    {
        return view('livewire.app.signature.main');
    }
    
}
