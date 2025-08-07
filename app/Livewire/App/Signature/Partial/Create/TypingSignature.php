<?php

namespace App\Livewire\App\Signature\Partial\Create;

use App\Services\SignatureDrawings\SaveService;
use App\Trait\HasNotify;

use App\Enums\Signatures\Type;

use Livewire\Component;



class TypingSignature extends Component {
    use HasNotify;
    
    public $ruleFonts;
    
    public function mount() {
        
        $this->ruleFonts = [
            [ "key" => Type::SIGNATURE->value,"minFontSize" => 12,"maxFontSize" => 48,"maxValue" => 30,"paddingX" => 0.25,"paddingY" => 0.2, ],
            [ "key" => Type::PARAF->value,"minFontSize" => 12,"maxFontSize" => 48,"maxValue" => 10,"paddingX" => 0.25,"paddingY" => 0.2, ],
        ];
    }
    
    public function saveNewSignature($data) {
        if (! is_array($data)) {
            $this->notify('danger', 'Invalid Signature', 'The signature data is not valid.');
            return;
        }
        
        $save = SaveService::handle($data);
        
        $variant = $save['status'] ? 'info' : 'danger';
        $title = $save['status'] ? 'Signature Saved' : 'Failed to Save';
        $message = $save['message'];
        
        $this->notify($variant, $title, $message);
        
        $this->dispatch('typenewsignaturecreate', [
            'status' => $save['status'],
            'message' => $title,
        ]);
        
        $this->dispatch('Refresh-New-Signature');
    }
    
    public function render()
    {
        return view('livewire.app.signature.partial.create.typing-signature');
    }
    
    
    
    
    
    // private function
    
    private function checkDrawData($data) {
        try {
            
            $properties = ['_token', 'value'];
            foreach($properties as $property) {
                if (!property_exists($data, $property)) {
                    throw new \Exception("Missing required property: '{$property}'");
                }
            }
            
            if (!is_array($data->value) || empty($data->value)) {
                throw new \Exception("No signature data found.");
            }
            
            foreach ($data->value as $value) {
                if (!in_array($value->key, $this->acceptKey)) {
                    throw new \Exception("Invalid signature type: '{$value->key}' is not accepted.");
                }
            }
            
            return (object)[
                'status' => true,
                'message' => 'Signature data validated successfully.',
            ];
            
        } catch (\Exception $e) {
            
            return (object)[
                'status' => false,
                'message' => $e->getMessage(),
            ];
            
        }
        
    }
    
    // info, success, warning, danger
    private function dispatchNotification($variant = 'info', $title, $message) {
        $this->dispatch('customnotify', (object) [
            'variant' => $variant,
            'sender' => 'System',
            'title' => $title,
            'message' => $message,
        ]);
    }
    
    
}
