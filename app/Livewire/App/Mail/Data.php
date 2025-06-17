<?php

namespace App\Livewire\App\Mail;

use Livewire\Attributes;
use Livewire\Component;

use Flasher\Laravel\Facade\Flasher;

class Data extends Component
{
    public $typeMail;
    public $searchMail;
    public $randMail;
    public $statusUpdateData = false;
    public $testDataHaveValue = [];
    
    private $acceptFilterName = ['type', 'search', 'page'];
    private $acceptFilterType = ['inbox', 'sent', 'draft', 'all'];
    
    public function mount() {
        $this->typeMail = request()->get('t') ?? 'inbox';
        $this->searchMail = request()->get('s') ?? '';
        $this->randMail = rand(10, 25);
    }
    
    #[Attributes\On('Mail-Filter-Data')]
    public function mailFilter($dataDispatch) {
        $localData = (object)$dataDispatch;
        
        $filterData = $localData->filter;
        $filterHaveValue = [];
        
        foreach($filterData as $itmFilter) {
            
            $itmFilter = (object) $itmFilter;
            try {
                if (!in_array($itmFilter->name, $this->acceptFilterName)) {
                    throw new \Exception('Filter name ' . $itmFilter->name . ' not found');
                }
                
                if (!$itmFilter->value) {
                    throw new \Exception('Filter value of ' . $itmFilter->name . ' not initialized');
                }
                
                $filterHaveValue[] = clone($itmFilter);
                
            } catch(\Exception $e) {
                
            }
            
            // dump($itmFilter->name);
        }
        
        $this->testDataHaveValue = $filterHaveValue;
        // dump(
        //     [
        //         (object) [
        //             'name' => 'data dispatch',
        //             'value' => $dataDispatch,
        //         ],
        //         (object) [
        //             'name' => 'filter data dispatch',
        //             'value' => $filterData,
        //         ],
        //         (object) [
        //             'name' => 'filter data have value',
        //             'value' => $filterHaveValue,
        //         ],
        //     ]
        // );
        // $this->searchMail = $localData->search;
    }
    
    private function updateDataMailType($filterName) {
        
    }
    
    public function render()
    {
        return view('livewire.app.mail.data');
    }
}
