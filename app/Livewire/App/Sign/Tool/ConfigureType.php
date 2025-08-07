<?php

namespace App\Livewire\App\Sign\Tool;

use Illuminate\Support\Facades\Auth;

use App\Utils\ModelUtils;

use App\Enums\Documents\Signature\Type as DocType;

use App\Trait\HasNotify;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes;

class ConfigureType extends Component
{
    use HasNotify;
    
    public ?string $id_document;
    public bool $is_redirect_to = false;
    
    public function mount(?string $id_document = null, bool $is_redirect_to = false) {
        $this->id_document = $id_document;
        $this->is_redirect_to = $is_redirect_to;
    }
    
    public function change_type(string $type, ?string $id_document = null) {
        
        $id_document = $id_document ?? $this->id_document;
        if (! $id_document) {
            $this->notify('warning', 'Document not found', 'Please select the document for which you want to set the signature type.');
            return;
        }
        
        $type = DocType::from_value($type);
        if (! $type) {
            $this->notify('warning', 'Invalid signature type', 'Please select an available signature type.');
            return;
        }
        
        $id_user = Auth::user()->id_user;
        $model = ModelUtils::createInstanceModel(\App\Models\Documents\Document::class);
        
        $exists = $model->query()
            ->where('owner_id', '=', $id_user)
            ->where($model->getKeyName(), '=', $id_document)
            ->exists();
        
        if (! $exists) {
            $this->notify('warning', 'Access denied', 'You do not have permission to set the signature type for this document.');
            return;
        }
        
        $model_sign = ModelUtils::createInstanceModel(\App\Models\Documents\Signatures::class);
        $model_sign_type = ModelUtils::createInstanceModel(\App\Models\Documents\SignaturesType::class);
        
        $find_sign = $model_sign->query()
            ->where($model->getKeyName(), '=', $id_document)
            ->first();
        
        if (! $find_sign) {
            $this->notify('warning', 'Signature data not found', 'Please double check the document or refresh.');
            return;
        }
        
        $find_type = $model_sign_type->query()
            ->where($model_sign->getKeyName(), '=', $find_sign->{$model_sign->getKeyName()})
            ->exists();
        
        if ($find_type) {
            $model_sign_type->query()
                ->where($model_sign->getKeyName(), '=', $find_sign->{$model_sign->getKeyName()})
                ->update([
                    'type' => $type->value,
                    'type_changed' => Carbon::now()
                ]);
            
            $this->notify('success', 'Signature type updated', 'Document signature type updated successfully.');
        } else {
            $model_sign_type->create([
                'id_document_signature' => $find_sign->{$model_sign->getKeyName()},
                'type' => $type->value,
                'type_changed' => Carbon::now(),
            ]);
            
            $this->notify('success', 'Signature type successfully set', 'The document signature type has been successfully determined.');
        }
        
        if ($this->is_redirect_to) {
            $this->notify('info', 'Redirecting to signature page', 'You will be redirected to the main signature page.');
            $this->dispatch('listen_is_redirect_to', [
                'url' => route('app.signs.main', ['id_document' => $id_document])
            ]);
        }
        
        $this->dispatch('listen_is_redirect_to');
    }
    
    public function render()
    {
        return view('livewire.app.sign.tool.configure-type');
    }
}
