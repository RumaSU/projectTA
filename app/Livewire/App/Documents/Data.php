<?php

namespace App\Livewire\App\Documents;

use Livewire\Attributes;
use Livewire\Component;

use Ramsey\Uuid\Uuid;

class Data extends Component
{
    public $searchDocument;
    #[Attributes\On('Document-Search-Page')]
    public function mailSearch($dataDispatch) {
        $localData = (object)$dataDispatch;
        
        dump($dataDispatch);
        $this->searchDocument = $localData->search;
        
        // $this->typeMail = $localData->page;
        // Flasher::success('Dispatch success');
        // flash()->success('Dispatch success');
    }
    
    #[Attributes\On('Document-Filter-Data')]
    public function dataFilterDocument($dataDispatch) {
        $localData = (object)$dataDispatch;
        
        // dump($dataDispatch);
    }
    
    // public function mount(){
    //     $dispatchDataInit = (object) [
    //         'message' => 'Dispatching from controller livewire',
    //         'from' => 'livewire',
    //     ];
    //     // $this->dispatch('Alpine-Init-Filter-Document', $dispatchDataInit);
    // }
    
    public function render()
    {
        return view('livewire.app.documents.data');
    }
}
