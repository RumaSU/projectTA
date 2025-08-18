<?php

namespace App\Livewire\App\Sign;

use App\Trait\HasNotify;
use Livewire\Component;
use Livewire\Attributes;

use App\Services\SignServices;

use App\Enums\Documents\Signature\Type as DocType;
use App\Enums\Documents\Signature\Status as DocStatus;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use Symfony\Component\Mime\MimeTypes;

use TCPDF;
use TCPDI;
use LibreSign\TcpdiParser\tcpdi_parser;

use Com\Tecnick\Pdf\Parser\Parser;
// use Smalot\PdfParser\Parser;

class Show extends Component
{
    use HasNotify;
    
    public string $id_document;
    public string $id_signature_type;
    public string $filename;
    
    public $document_version;
    public $file_entity;
    public $file_disk_entity;
    public $file_disk;
    public $file_disk_token;
    public $is_owner;
    
    public DocType $doc_type;
    public DocStatus $doc_status;
    
    
    
    
    
    public function mount(string $id_document) {
        $this->id_document = $id_document;
        
        [
            $this->document_version,
            $this->file_entity, 
            $this->file_disk_entity, 
            $this->file_disk, 
            $this->file_disk_token
        ] = SignServices::get_file($id_document);
        
        // dump(
        //     $this->document_version,
        //     $this->file_entity, 
        //     $this->file_disk_entity, 
        //     $this->file_disk, 
        //     $this->file_disk_token
        // );
        
        if (
            ! $this->document_version ||
            ! $this->file_entity ||
            ! $this->file_disk_entity ||
            ! $this->file_disk ||
            ! $this->file_disk_token
        ) {
            
            $this->dispatch('Not-Found-File', [
                'status' => false
            ]);
            
            return $this->redirectRoute('app.signs.main', ['id_document' => 'not-found'], navigate: true);
            // return redirect()->route('app.signs.main', ['id_document' => 'not-found']);
        }
        
        $this->doc_type = DocType::get_signature_type($id_document);
        if (! $this->doc_type || $this->doc_type === DocType::UNCATEGORIZED) {
            $this->dispatch('Document-Type-Uncategorized', [
                'status' => false
            ]);
        }
        
        $this->doc_status = DocStatus::get_signature_status($id_document);
        
        $this->is_owner = SignServices::is_owner($id_document, Auth::user()->id_user);
        
        $filename = $this->file_entity->file_client_name;
        $this->filename = $filename . "." . $this->file_entity->extension ?? MimeTypes::getDefault()->getExtensions($this->file_disk_entity->mime_type)[0];

    }
    
    #[Attributes\On('Add-Id-Signature-Type')]
    public function update_to_finalize($event) {
        if (!is_array($event) || empty($event['signature_type_id'] || empty($event['token']))) {
            return $this->notify('danger', 'Invalid event data received', 'Please check your event data');
        }
        
        if ($event['token'] !== csrf_token()) {
            return $this->notify('danger', 'Invalid CSRF token', 'Please refresh the page and try again');
        }
        
        $id = $event['signature_type_id'];
        
        
        $this->id_signature_type = $id;
    }
    
    #[Attributes\On('Update-Show-File')]
    public function update_show_file($data) {
        if (! is_array($data)) {
            return;
        }
        
        if ($data['status']) {
            return $this->redirectRoute('app.signs.main', ['id_document' => $this->id_document], navigate: true);
        }
    }
    
    public function render()
    {
        return view('livewire.app.sign.show');
    }
}
