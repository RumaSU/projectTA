<?php

namespace App\Livewire\App\Documents;

use App\Trait\HasNotify;
use Livewire\Attributes;
use Livewire\Component;

use App\Services\Documents\GetService;

use App\Utils\ModelUtils;

use App\Enums\Documents\Signature\Type as DocType;

use Illuminate\Support\Facades\Auth;

use Ramsey\Uuid\Uuid;

class Data extends Component
{
    use HasNotify;
    public $searchDocument;
    public $filterDocument = null;
    
    // public $listDocument;
    
    
    
    #[Attributes\On('Document-Search-Page')]
    public function documentSearch($dataDispatch) {
        $localData = (object)$dataDispatch;
        
        $this->searchDocument = $localData->search;
        
        $this->updateData();
    }
    
    #[Attributes\On('Document-Filter-Data')]
    public function dataFilterDocument($dataDispatch) {
        $localData = json_decode(json_encode($dataDispatch));
        $this->filterDocument = $localData;
        
        $this->updateData();
    }
    
    
    public function actionSign(string $id_document) {
        $id_user = Auth::user()->id_user;
        
        $list = GetService::handle(
            $id_user,
            'all',
            'all',
            null
        );
        
        $filter = collect($list)
            ->filter(fn($item) => $item->id_document === $id_document)
            ->first()
            ->toArray();
        
        $type = DocType::from_value($filter['type']);
        
        if (! $type) {
            DocType::get_signature_type($filter['id_document']);
            $this->updateData();
            $this->actionSign($id_document);
            
            return;
        }
        
        if ($type === DocType::UNCATEGORIZED) {
            $model = ModelUtils::createInstanceModel(\App\Models\Documents\Document::class);
            
            $is_owner = $model->query()
                ->where('owner_id', '=', $id_user)
                ->where($model->getKeyName(), '=', $id_document)
                ->first();
            
            if ($is_owner) {
                
                $this->dispatch('show_configure_type_signature', [
                    'id_document' => $id_document
                ]);
                
            } else {
                $this->notify(
                    'warning',
                    'Signature cannot be performed yet',
                    'Please contact the document owner to set the signature type first.'
                );
            }
            
            // $is_owner = 
            
        } else {
            $this->notify('info', 'Redirecting to signature page', 'You will be redirected to the main signature page.');
            $this->dispatch('listen_is_redirect_to', [
                'url' => route('app.signs.main', ['id_document' => $id_document])
            ]);
            
        }
        
        $this->updateData();
    }
    
    
    
    
    public function placeholder() {
        return view('livewire.app.documents.placeholder');
    }
    
    public function mount(){
        $dispatchDataInit = (object) [
            'message' => 'Dispatching from controller livewire',
            'from' => 'system',
        ];
        $this->dispatch('alpineinitfilterdocument', $dispatchDataInit);
        $this->updateData();
    }
    
    public function render()
    {
        return view('livewire.app.documents.data');
    }
    
    public $listDocument;
    
    private function updateData() {
        
        $status = $this->filterDocument?->filter_status ?? 'all';
        $type = $this->filterDocument?->filter_type ?? 'all';
        
        $this->listDocument = GetService::handle(
            Auth::user()->id_user,
            $status,
            $type,
            $this->searchDocument ?? null
        );
        
    }
    
    
}
