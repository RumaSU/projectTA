<?php

namespace App\Livewire\App\Signature;

use App\Library\Signatures\Helper as SignatureHelper;
use App\Library\Signatures\ModelUtils as SignatureModelsUtils;
use App\Library\Helper as LibHelper;

use App\Models\Signatures;

use App\Services\SignatureDrawings\GetService;
use App\Services\SignatureDrawings\UpdateDefaultService;
use App\Services\SignatureDrawings\DeleteService;
use App\Libraries\ArrayHelper;
use App\Trait\HasNotify;

use Illuminate\Support\Facades\Auth;


use Livewire\Component;
use Livewire\Attributes;

#[Attributes\Lazy()]
class Data extends Component
{
    use HasNotify;
    
    public $list_signatures;
    public $default_signature;
    
    public $load_count = 0;
    public $show_detail = true;
    
    private const UPDATE_PARAM = ['_token', 'id'];
    
    
    public function placeholder() {
        return view('livewire.app.signature.partial.data.placeholder');
    }
    
    public function mount() {
        $values = GetService::default(Auth::user()->id_user);
        $this->default_signature = array_values($values)[0] ?? null;
        
        $this->list_signatures = GetService::list(Auth::user()->id_user);
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
        if (! is_array($data)) {
            $this->notify('danger', 'Invalid Data', 'The data provided is invalid.');
            return;
        }
        
        if (! ArrayHelper::key_exists(static::UPDATE_PARAM, $data)) {
            $this->notify('danger', 'Incomplete Parameter', 'Update parameter not found.');
            return;
        }
        
        if (! ArrayHelper::key_exists('_token', $data) || $data['_token'] !== csrf_token()) {
            $this->notify('danger', 'Invalid Token', 'Authentication token is invalid or has expired.');
            return;
        }
        
        $resultUpdate = UpdateDefaultService::handle($data['id'], Auth::user()->id_user);
        $variant = $resultUpdate['status'] ? 'success' : 'danger';
        $this->notify(
            $variant,
            $resultUpdate['status'] ? 'Update Successful' : 'Update Failed',
            $resultUpdate['message']
        );
        
        if ($resultUpdate['status']) {  
            return $this->mount();
        }
    }
    
    public function deleteSignatures($data) {
        if (! is_array($data)) {
            $this->notify('danger', 'Invalid Data', 'The data provided is invalid.');
            return;
        }
        
        if (! ArrayHelper::key_exists(static::UPDATE_PARAM, $data)) {
            $this->notify('danger', 'Incomplete Parameter', 'Update parameter not found.');
            return;
        }
        
        if (! ArrayHelper::key_exists('_token', $data) || $data['_token'] !== csrf_token()) {
            $this->notify('danger', 'Invalid Token', 'Authentication token is invalid or has expired.');
            return;
        }
        
        
        $resultDelete = DeleteService::handle($data['id'], Auth::user()->id_user);
        $variant = $resultDelete['status'] ? 'success' : 'danger';
        $this->notify(
            $variant,
            $resultDelete['status'] ? 'Delete Successful' : 'Delete Failed',
            $resultDelete['message']
        );
        
        if ($resultDelete['status']) {  
            return $this->mount();
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
