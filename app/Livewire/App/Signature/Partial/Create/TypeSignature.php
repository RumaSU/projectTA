<?php

namespace App\Livewire\App\Signature\Partial\Create;

use App\Library\SignatureHelper;
use App\Library\Helper as LibHelper;

use App\Models\Signatures;

use Illuminate\Support\Facades\Auth;

use Livewire\Component;

class TypeSignature extends Component
{
    public $acceptColor;
    public $acceptStyle;
    public $ruleType;
    public $keyType;
    private $acceptKey = ['signature', 'paraf'];
    
    public function mount() {
        $tempColors = [
            ['color' => '#000000', 'text' => 'Black', 'default' => true,], 
            ['color' => '#ff0000', 'text' => 'Red', 'default' => false,], 
            ['color' => '#00ff00', 'text' => 'Green', 'default' => false,], 
            ['color' => '#0000ff', 'text' => 'Blue', 'default' => false,], 
        ];
        
        $tempStyles = [
            [ "font" => "--main-font-pacifico", "text" => "Pacifico", "value" => "Pacifico, cursive", "default" => true, ],
            [ "font" => "--main-font-dancing-script", "text" => "Dancing Script", "value" => "Dancing Script, cursive", "default" => false, ],
            [ "font" => "--main-font-great-vibes", "text" => "Great Vibes", "value" => "Great Vibes, cursive", "default" => false, ],
            [ "font" => "--main-font-satisfy", "text" => "Satisfy", "value" => "Satisfy, cursive", "default" => false, ],
            [ "font" => "--main-font-allura", "text" => "Allura", "value" => "Allura, cursive", "default" => false, ],
            [ "font" => "--main-font-alex-brush", "text" => "Alex Brush", "value" => "Alex Brush, cursive", "default" => false, ],
            [ "font" => "--main-font-signika", "text" => "Signika", "value" => "Signika", "default" => false, ],
            [ "font" => "--main-font-mr-dafoe", "text" => "Mr Dafoe", "value" => "Mr Dafoe, cursive", "default" => false, ],
            [ "font" => "--main-font-homemad-apple", "text" => "Homemad Apple", "value" => "Homemade Apple, cursive", "default" => false, ],
        ];
        
        $tempRulesType = [
            [ "key" => "signature","minFontSize" => 12,"maxFontSize" => 48,"maxValue" => 30,"paddingX" => 0.25,"paddingY" => 0.2, ],
            [ "key" => "paraf","minFontSize" => 12,"maxFontSize" => 48,"maxValue" => 10,"paddingX" => 0.25,"paddingY" => 0.2, ],
        ];
        
        $this->acceptColor = json_encode($tempColors);
        $this->acceptStyle = json_encode($tempStyles);
        $this->ruleType = json_encode($tempRulesType);
    }
    
    
    
    public function saveType($data) {
        if (! is_array($data) || ! isset($data['value'])) return;
        
        $objData = json_decode(json_encode($data));
        
        // dump($objData);
        
        try {
            $checkData = $this->checkDrawData($objData);
            if (! $checkData->status ) throw new \Exception($checkData->message);
            
            $uuidSignature = LibHelper::generateUniqueUuId('v4', 'id_signature', Signatures\Signature::class);
            $saveSignature = Signatures\Signature::create([
                'id_signature' => $uuidSignature,
                'id_user' => Auth::user()->id_user,
                'default' => true,
            ]);
            
            if (! $saveSignature) {
                SignatureHelper::rollbackSignatures($uuidSignature);
                throw new \Exception('Failed to create signature group record. Please try again.');
            }
            
            $dataValues = $objData->value;
            foreach($dataValues as $value) {
                $saveResult = SignatureHelper::saveSignatures($value, $uuidSignature);
                
                if (! $saveResult->status) {
                    throw new \Exception("Failed to save {$value->key}: {$saveResult->message}");
                }
                
            }
            
            $this->dispatchNotification(
                'success',
                'Signature Saved',
                'All signature data has been successfully saved.'
            );

            $this->dispatch('typenewsignaturecreate', [
                'status' => true,
                'message' => 'All signature data saved successfully.',
            ]);
            return;
            
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $this->dispatchNotification(
                'danger',
                'Signature Failed',
                "Failed to save signature or paraf: {$message}"
            );
            
            $this->dispatch('typenewsignaturecreate', [
                'status' => false,
                'message' => "Signature submission failed: {$message}"
            ]);
            
            return;
        }
    }
    
    
    public function render()
    {
        return view('livewire.app.signature.partial.create.type-signature');
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
