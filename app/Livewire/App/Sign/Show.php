<?php

namespace App\Livewire\App\Sign;

use Livewire\Component;
use Livewire\Attributes;

use App\Services\SignServices;

use App\Enums\Documents\Signature\Type as DocType;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Mime\MimeTypes;

class Show extends Component
{
    public string $id_document ;
    public string $filename;
    
    public $document_version;
    public $file_entity;
    public $file_disk_entity;
    public $file_disk;
    public $file_disk_token;
    public $is_owner;
    
    public DocType $doc_type;
    
    public function mount(string $id_document) {
        $this->id_document = $id_document;
        
        [
            $this->document_version,
            $this->file_entity, 
            $this->file_disk_entity, 
            $this->file_disk, 
            $this->file_disk_token
        ] = SignServices::get_file($id_document);
        
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
            
            return;
        }
        
        $this->doc_type = DocType::get_signature_type($id_document);
        if (! $this->doc_type || $this->doc_type === DocType::UNCATEGORIZED) {
            $this->dispatch('Document-Type-Uncategorized', [
                'status' => false
            ]);
        }
        
        $this->is_owner = SignServices::is_owner($id_document, Auth::user()->id_user);
        
        $filename = $this->file_entity->file_client_name;
        $this->filename = $filename . "." . $this->file_entity->extension ?? MimeTypes::getDefault()->getExtensions($this->file_disk_entity->mime_type)[0];

    }
    
     
    
    public function render()
    {
        return view('livewire.app.sign.show');
    }
}
