<?php

namespace App\Livewire\App\Home\Component;

use Livewire\Component;
use Livewire\Attributes;

use App\Utils\ModelUtils;

use App\Services\Certificates\Service as CertService;
use App\Services\Documents\GetService;

use Illuminate\Support\Facades\Auth;

class Cards extends Component
{
    
    public $total_signed;
    public $total_document;
    public $certificate_status;
    public $certificate_info;
    
    public function mount() {
        $this->getTotalDocumentSigned();
        $this->getTotalDocument();
        $this->getCertificateStatus();
    }
    
    
    protected function getTotalDocumentSigned() {
        $user = Auth::user();
        
        $list_id_document = GetService::handle($user->id_user)->pluck('id_document');
        
        $this->total_signed = ModelUtils::createInstanceModel(\App\Models\Documents\Signed::class)
            ->query()
            ->whereIn('id_document', $list_id_document)
            ->count();
        
    }
    
    protected function getTotalDocument() {
        $user = Auth::user();
        
        $this->total_document = count(GetService::handle($user->id_user));
    }
    
    protected function getCertificateStatus() {
        $user = Auth::user();
        
        $this->certificate_info = CertService::handler()->getLatestCertificateUser($user->id_user);
        $this->certificate_status = CertService::handler()->isCertificateValid($this->certificate_info->id_certificate);
        
    }
    
    
    
    
    public function render()
    {
        return view('livewire.app.home.component.cards');
    }
}
