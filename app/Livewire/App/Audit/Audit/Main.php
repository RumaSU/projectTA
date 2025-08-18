<?php

namespace App\Livewire\App\Audit\Audit;

use Livewire\Component;
use Livewire\Attributes;

use App\Utils\ModelUtils;
use Illuminate\Support\Facades\Auth;

class Main extends Component
{
    public $can_access;
    public $is_found;
    public $id_document;
    
    public function mount($id) {
        
        $user = Auth::user();
        
        $find = ModelUtils::createInstanceModel(\App\Models\Documents\Document::class)
            ->query()
            ->where('id_document', '=', $id)
            ->first();
        
        if (! $find) {
            return $this->is_found = false;
        }
        
        $this->id_document = $id;
        
        if ($find->owner_id === $user->id_user) {
            return $this->can_access = true;
        }
        
        $this->is_found = true;
        
        $this->can_access = ModelUtils::createInstanceModel(\App\Models\Documents\DocumentCollaborator::class)
            ->query()
            ->where('id_document', '=', $id)
            ->where('id_user', '=', $user->id_user)
            ->exists();
        
        if (! $this->can_access) {
            return;
        }
        
        
    }
    
    #[Attributes\Layout('livewire.layout.audit.template')]
    public function render()
    {
        return view('livewire.app.audit.audit.main');
    }
}
