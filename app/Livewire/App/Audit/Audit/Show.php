<?php

namespace App\Livewire\App\Audit\Audit;

use Livewire\Component;
use Livewire\Attributes;

use App\Utils\ModelUtils;
use App\Libraries\Common;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use TCPDI;

class Show extends Component
{
    
    public $id_document;
    public bool $is_signed;
    
    
    public function mount($id_document) {
        
        $this->id_document = $id_document;
        $this->is_signed = ModelUtils::createInstanceModel(\App\Models\Documents\Signed::class)
            ->query()
            ->where('id_document', '=', $id_document)
            ->exists();
        
        
        
    }
    
    
    #[Attributes\On('Stream-Audit-Main-Info')]
    public function streamMainInfo() {
        
        $document = ModelUtils::createInstanceModel(\App\Models\Documents\Document::class)
            ->find($this->id_document);
        
        $documentInformation = ModelUtils::createInstanceModel(\App\Models\Documents\DocumentInformation::class)
            ->find($this->id_document);
            
        $documentVersion = ModelUtils::createInstanceModel(\App\Models\Documents\DocumentVersions::class)
            ->where('id_document', '=', $document->id_document)
            ->orderBy('version')
            ->get();
        
        
        $owner = ModelUtils::createInstanceModel(\App\Models\Users\User::class)
            ->find($document->owner_id);
        
        $documentSigned = ModelUtils::createInstanceModel(\App\Models\Documents\Signed::class)
            ->where('id_document', '=', $document->id_document)
            ->first();
        
        $documentAudit = ModelUtils::createInstanceModel(\App\Models\Audit\Document::class)
            ->where('id_document', '=', $document->id_document)
            ->latest('logged_at')
            ->first();
        
        $documentFile = ModelUtils::createInstanceModel(\App\Models\Documents\DocumentFile::class)
            ->where('id_document_version', '=', $documentVersion[count($documentVersion) - 1]->id_document_version)
            ->first();
        
        $file_document = ModelUtils::createInstanceModel(\App\Models\Files\Entity\Documents::class)
            ->where('id_file_document', '=', $documentFile->id_file_document)
            ->first();
        
        $file_disk = ModelUtils::createInstanceModel(\App\Models\Files\Disk::class)
            ->find($file_document->id_file_disk);
        
        $pathFile = Storage::disk($file_disk->disk)->path($file_disk->path);
        $pdf = new TCPDI();
        $countPage = $pdf->setSourceFile($pathFile);
        
        $outputOwner = 'N/A';
        if ($owner) {
            $outputOwner = "
                {$owner->userPersonal->fullname}
                <a href='mailto:{$owner->email}' class='text-blue-600 bg-blue-50 px-2 py-1 rounded'>&lt;{$owner->email}&gt;</a>
            ";
        }
        
        $timezone = session()->get('timezone');
        $outputFinalized = 'N/A';
        if ($documentSigned) {
            $outputFinalized = Carbon::create( $documentSigned->signed_at)->timezone($timezone)->format('F j, Y - H:i:s');
        }
        
        $created = Carbon::create($documentVersion[0]->created_at)->timezone($timezone)->format('F j, Y - H:i:s');
        $modified = Carbon::create($documentAudit->logged_at)->timezone($timezone)->format('F j, Y - H:i:s');
        
        $outputSize = Common::formatBytes($file_disk->size_byte) . " ({$file_disk->size_byte})";
        
        $styleStatus = \App\Enums\Documents\Signature\Status::get_signature_status($this->id_document)->get_style();
        
        $outputStatusInfo = "
            This document is 
            <div class='status-info px-4 py-0.5 rounded-lg {$styleStatus['backgroundBold']}'>
                <div class='text-status text-xs {$styleStatus['textColorBold']}'>
                    <p>{$styleStatus['text']}</p>
                </div>
            </div>
        ";
        
        $outputDocumentID = "
            <code class='bg-gray-100 px-2 py-1 rounded text-sm'>{$this->id_document}</code>
        ";
        
        $outputHashType = "
            <code class='bg-gray-100 px-2 py-1 rounded text-sm block break-words'>
                {$file_disk->hash_type}
            </code>
        ";
        
        $outputHash = "
            <code class='bg-gray-100 px-2 py-1 rounded text-sm block break-words'>
                {$file_disk->hash_file}
            </code>
        ";
        
        $list_render = [
            ['label' => 'Status', 'value' => $outputStatusInfo],
            ['label' => 'Name', 'value' => $documentInformation->name],
            ['label' => 'Document ID', 'value' => $outputDocumentID],
            ['label' => 'Current Version', 'value' => $documentVersion[count($documentVersion) - 1]->version],
            ['label' => 'Owner File', 'value' => $outputOwner],
            ['label' => 'Created File', 'value' => $created],
            ['label' => 'Finalized File', 'value' => $outputFinalized],
            ['label' => 'Last Modified', 'value' => $modified],
            ['label' => 'File Size', 'value' => $outputSize],
            ['label' => 'Hash Type', 'value' => $outputHashType],
            ['label' => 'Hash', 'value' => $outputHash],
            ['label' => 'Page Count', 'value' => $countPage],
        ];
        
        $html = '';
        
        foreach ($list_render as $render) {
            
            $html .= view('livewire.app.audit.component.item', $render); 
            
        }
        
        $this->stream('mainInfoAudit', $html, true);
    }
    
    
    #[Attributes\On('Stream-Audit-Signed-Info')]
    public function streamSignedInformation() {
        
        $documentSigned = ModelUtils::createInstanceModel(\App\Models\Documents\Signed::class)
            ->query()
            ->where('id_document', '=', $this->id_document)
            ->orderBy('signed_at')
            ->get();
        
        $outputCertificateUsed = count($documentSigned)
            ? count($documentSigned)
            : 'N/A';
        
        $styleStatusSigned = $this->is_signed
            ? [
                'background' => 'bg-blue-600',
                'color' => 'text-white',
                'text' => 'Signed'
            ]
            : [
                'background' => 'bg-gray-600',
                'color' => 'text-white',
                'text' => 'Unsigned'
            ];
        
        $outputStatusInfo = "
            This document is
            <div class='status-info px-4 py-0.5 rounded-lg {$styleStatusSigned['background']}'>
                <div class='text-status text-xs {$styleStatusSigned['color']}'>
                    <p>{$styleStatusSigned['text']}</p>
                </div>
            </div>
        ";
        
        
        
        $list_render = [
            ['label' => 'Status', 'value' => $outputStatusInfo],
            ['label' => 'Certificate Used', 'value' => $outputCertificateUsed],
            
        ];
        
        $html = '';
        
        foreach ($list_render as $render) {
            
            $html .= view('livewire.app.audit.component.item', $render); 
            
        }
        
        $this->stream('signedInfo', $html, true);
        
        $this->streamCertificateInformation($documentSigned);
    }
    
    protected function streamCertificateInformation($documentSigned) {
        if (empty($documentSigned)) {
            return;
        }
        
        $html = '';
        
        foreach($documentSigned as $idx => $signed) {
            
            $certificate = ModelUtils::createInstanceModel(\App\Models\Certificates\Certificate::class)
                ->find($signed->id_certificate);
            
            $certificateIdentity = ModelUtils::createInstanceModel(\App\Models\Certificates\CertificateIdentity::class)
                ->query()
                ->where('id_certificate', '=', $certificate->id_certificate)
                ->first();
            
            $signedIdentifier = ModelUtils::createInstanceModel(\App\Models\Documents\SignedQR::class)
                ->where('id_document_signed', '=', $signed->id_document_signed)
                ->first();
            
            $signer = ModelUtils::createInstanceModel(\App\Models\Users\User::class)
                ->find($certificate->id_user);
            
            $html .= view('livewire.app.audit.component.item-certificate', [
                'count' => $idx+1,
                'certificate' => $certificate,
                'certificateIdentity' => $certificateIdentity,
                'signer' => $signer,
                'signedIdentifier' => $signedIdentifier,
            ])->render();
            
            
            // $signer = ModelUtils::createInstanceModel(\App\Models\Certificates\Certificate::class)
            //     ->find($signed->id_user)
            
            
        }
        
        $this->stream('signedCertificateInfo', $html, true);
        
    }
    
    
    
    #[Attributes\On('Stream-Audit-Trail')]
    public function streamAuditTrail() {
        
        $audits = ModelUtils::createInstanceModel(\App\Models\Audit\Document::class)
            ->query()
            ->where('id_document', '=', $this->id_document)
            ->orderBy('logged_at', 'asc')
            ->get();
        
        $html = '';
        
        foreach($audits as $audit) {
            $html .= view('livewire.app.audit.component.item-audit', ['audit' => $audit])
                ->render();       
        }
            
        $this->stream('infoAuditTrait', $html, true);
        
    }
    
    
    public function render()
    {
        return view('livewire.app.audit.audit.show');
    }
}
