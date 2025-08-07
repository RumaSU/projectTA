<?php

namespace App\Livewire\App\Upload\Partials;

use Livewire\Attributes;
use Livewire\Component;

use App\Library\Helper as LibHelper;
use App\Models\Token\Upload as UploadModels;

use Carbon\Carbon;

class DocumentFunction extends Component
{
    public function render()
    {
        return view('livewire.app.upload.partials.document-function');
    }
    
    #[Attributes\On('create_token_upload_file')]
    public function createNewTokenFile($data) {
        if (gettype($data) != 'array') return;
        
        $objData = json_decode(json_encode($data));
        
        UploadModels::create([
            'session_id' => session()->getId(),
            'token' => $objData->tokenUpload,
            'token_resumable' => $objData->tokenResumable,
            'expired_at' => Carbon::now()->addDays(3),
        ]);
    }
    
}
