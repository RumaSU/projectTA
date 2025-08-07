<?php

namespace App\Livewire\App\Upload;

use Livewire\Attributes;
use Livewire\Component;

use App\Library\Helper as LibHelper;
use App\Models\Token\Upload as UploadModels;

class Documents extends Component
{
    #[Attributes\Layout('livewire.layout.dashboard.template')]
    public function render()
    {
        return view('livewire.app.upload.documents');
    }
    
}
