<?php

namespace App\Livewire\App\Sign\Tool;

use Illuminate\Support\Facades\Auth;

use App\Enums\Documents\Signature\Type as DocType;
use App\Enums\Documents\Role;
use App\Enums\Signatures\Variant;
use App\Utils\ModelUtils;

use App\Services\SignatureDrawings\GetService;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\ValidationException;

class AddSignature extends Component
{
    
    public bool $is_loaded = false;
    public bool $is_have_signature;
    public bool $is_list_loaded = false;
    public bool $is_owner = false;
    
    public $list_signature;
    public $default_signature;
    public string $id_document;
    public $doc_type;
    
    public function mount(string $id_document) {
        $this->id_document = $id_document;
    }
    
    public function mounting(string $id_document) {
        $this->doc_type = DocType::get_signature_type($id_document);
        
        if ($this->doc_type && $this->doc_type !== DocType::UNCATEGORIZED) {
            $this->dispatch('rough-get-list-signature-user');
            
        }
        
        $this->is_loaded = true;
    }
    
    
    
    
    public function render()
    {
        return view('livewire.app.sign.tool.add-signature');
    }
    
    
    #[Attributes\On('rough-get-list-signature-user')]
    public function get_signature() {
        $model = ModelUtils::createInstanceModel(\App\Models\Signatures\Signature::class);
        $id_user = Auth::user()->id_user;
        
        $exists = $model->query()
            ->where('id_user', '=', $id_user)
            ->exists()
            ;
        
        if (! $exists) {
            $this->is_have_signature = false;
            $this->is_list_loaded = true;
            return;
        }
        
        $this->is_have_signature = true;
        
        $this->default_signature = GetService::default($id_user);
        $this->list_signature = GetService::list($id_user);
        
        $this->is_list_loaded = true;
    }
    
    
    #[Attributes\On('Add-Image-To-PDF')]
    public function listen_add_image_to_pdf($event) {
        
        if (!is_array($event) || empty($event['signature_item'])) {
            return;
        }
        
        $signature_type = reset($event);
        
        $model = ModelUtils::createInstanceModel(\App\Models\Signatures\SignatureDrawings::class);
        $drawing = $model->query()
            ->where('id_signature_type', '=', $signature_type['id_signature_type'])
            ->where('variant', '=', Variant::ORIGINAL->value)
            ->first();
        
        if (!$drawing) return;
        
        $data = "placholder;{$signature_type['id_signature_type']}";
        
        $writer = new PngWriter();
        
        $qrCode = new QrCode(
            $data,
            new Encoding('UTF-8'),
            ErrorCorrectionLevel::Low,
            250,
            0,
            RoundBlockSizeMode::Margin,
            new Color(0, 0, 0),
            new Color(255, 255, 255, 127)
        );
        
        $result = $writer->write($qrCode);
        
        
        $this->dispatch('signature_added_to_pdf', [
            'base64' => $result->getDataUri(),
            'mime' => $result->getMimeType(),
            'page' => $event['page'] ?? 1,
            'x' => $event['x'] ?? 0.5,
            'y' => $event['y'] ?? 0.5,
        ]);
        
        // dump($this->update_signer_data($signature_type['id_signature_type'], $drawing));
    }
    
    
    private function update_signer_data($id_signature_type, $drawing) {
        
        $id_user = Auth::user()->id_user;
        $is_owner = false;
        
        $model = ModelUtils::createInstanceModel(\App\Models\Documents\Document::class);
        $find = $model->query()
            ->find($this->id_document);
        
        if (! $find) {
            return;
        }
        $is_owner = $id_user === $find->owner_id;
        
        
        $model_collab = ModelUtils::createInstanceModel(\App\Models\Documents\DocumentCollaborator::class);
        $find_collab = $model_collab->query()
            ->where('id_document', '=', $this->id_document)
            ->where('id_user', '=', $id_user)
            ->first();
        
        if (! $find_collab) {
            
            if (! $is_owner) {
                
                return;
            }
            
            $id = ModelUtils::generateNewUuid(\App\Models\Documents\DocumentCollaborator::class);
            
            $model_collab->create([
                'id_document_collaborator' => $id,
                'id_user' => $id_user,
                'id_document' => $this->id_document,
                'role' => Role::OWNER->value,
                'role_changed' => Carbon::now(),
            ]);
            
            $find_collab = $model_collab->query()
                ->find($id);
        }
        
        $model_sign = ModelUtils::createInstanceModel(\App\Models\Documents\Signatures::class);
        
        $find_sign = $model_sign->query()
            ->where($model->getKeyName(), '=', $this->id_document)
            ->first()
            ;
        
        if (! $find_sign) {
            
            return;
        }
        
        if ($is_owner) {
            
            $model_signer = ModelUtils::createInstanceModel(\App\Models\Documents\SignaturesSigner::class);
            $find_signer = $model_signer->query()
                ->where($model_sign->getKeyName(), '=', $find_sign->{$model_sign->getKeyName()})
                ->where($model_collab->getKeyName(), '=', $find_collab->{$model_collab->getKeyName()})
                ->first();
            
            if (! $find_signer) {
                
                
                
            }
                
            
            
        }
        
        
        
        
        
        
        
    }
    
}
