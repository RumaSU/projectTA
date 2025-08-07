<?php

namespace App\Livewire\App\Signature\Partial\Create;

use App\Services\SignatureDrawings\SaveService;
use App\Trait\HasNotify;

use Livewire\Component;

class DrawSignature extends Component {
    use HasNotify;
    
    // public $templateColor = [];
    // public $typeSignature = [];
    
    public function mount() {
        // if ($this->statusMount) {
        //     dump('Component remounted.');
        // }
        
        // $this->statusMount = true;
        // $this->typeSignature = json_encode(Type::get_map_value());
        // $this->templateColor = json_encode(Color::get_mapped_colors());
    }
    
    public function saveNewSignature($data) {
        if (! is_array($data)) {
            $this->notify('danger', 'Invalid Signature', 'The signature data is not valid.');
            return;
        }
        
        $save = SaveService::handle($data);
        
        $variant = $save['status'] ? 'info' : 'danger';
        $title = $save['status'] ? 'Signature Saved' : 'Failed to Save';
        $message = $save['message'];
        
        $this->notify($variant, $title, $message);
        
        $this->dispatch('drawnewsignaturecreate', [
            'status' => $save['status'],
            'message' => $title,
        ]);
        
        $this->dispatch('Refresh-New-Signature');
    }
    
    public function render()
    {
        return view('livewire.app.signature.partial.create.draw-signature');
    }
}
