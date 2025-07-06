<?php

namespace App\Livewire\App\Signature;

use App\Library\Signatures\Helper as SignatureHelper;
use App\Library\Signatures\ModelUtils as SignatureModelsUtils;
use App\Library\Helper as LibHelper;

use App\Models\Signatures;

use Illuminate\Support\Facades\Auth;

use Livewire\Component;
use Livewire\Attributes;

#[Attributes\Lazy()]
class Data extends Component
{
    public $list_signatures;
    public $default_signature;
    
    public $load_count = 0;
    public $show_detail = true;
    
    
    public function placeholder() {
        return view('livewire.app.signature.partial.data.placeholder');
    }
    
    public function mount() {
        $this->default_signature = SignatureModelsUtils::getListSignaturesImages(Auth::user()->id_user, true)[0] ?? null;
        $this->list_signatures = SignatureModelsUtils::getListSignaturesImages(Auth::user()->id_user, false);
    }
    
    
    public function render()
    {
        return view('livewire.app.signature.data');
    }
    
    #[Attributes\Renderless]
    public function changeShowDetail() {
        // 
        $this->show_detail = ! $this->show_detail;
        // return $this->skipRender();
    }
    
    public function updateDefaultSignatures($data) {
        $objData = json_decode(json_encode($data));
        if ($objData->_token != csrf_token()) return;
        
        $resultUpdate = SignatureModelsUtils::updateSignatureDefault($objData->id, Auth::user()->id_user);
        if ($resultUpdate->status) {
            $this->dispatchNotification('success', 'Default Signature Set', $resultUpdate->message);
            return $this->mount();
        } else {
            $this->dispatchNotification('danger', 'Failed to Set Default Signature', $resultUpdate->message);
            return;
        }
        
    }
    
    public function deleteSignatures($data) {
        $objData = json_decode(json_encode($data));
        if ($objData->_token != csrf_token()) return;
        
        $resultDelete = SignatureModelsUtils::deleteSignature($objData->id, Auth::user()->id_user);
        if ($resultDelete->status) {
            $this->dispatchNotification('success', 'Deleted', $resultDelete->message);
            return $this->mount();
        } else {
            $this->dispatchNotification('danger', 'Failed to Deleted Signature', $resultDelete->message);
            return;
        }
    }
    
    #[Attributes\On('Refresh-New-Signature')]
    public function refreshNewSignature() {
        $this->mount();
    }
    
    
    
    // info, success, warning, danger
    private function dispatchNotification($variant = 'info', $title, $message) {
        $this->dispatch('customnotify', (object) [
            'variant' => $variant,
            'sender' => 'System',
            'title' => $title,
            'message' => $message,
        ]);
    }
    
}
