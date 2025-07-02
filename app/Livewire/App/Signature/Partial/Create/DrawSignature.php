<?php

namespace App\Livewire\App\Signature\Partial\Create;

use App\Library\SignatureHelper;
use App\Library\Helper as LibHelper;

use App\Models\Signatures;

use Illuminate\Support\Facades\Auth;

use Livewire\Component;

class DrawSignature extends Component
{
    
    public $acceptColor;
    public $statusMount = false;
    public $keyDraw;
    private $acceptKey = ['signature', 'paraf'];
    
    public function mount() {
        if ($this->statusMount) {
            dump('Component remounted.');
        }
        $tempColor = [
            ['color' => '#000000', 'text' => 'Black', 'default' => true,], 
            ['color' => '#ff0000', 'text' => 'Red', 'default' => false,], 
            ['color' => '#00ff00', 'text' => 'Green', 'default' => false,], 
            ['color' => '#0000ff', 'text' => 'Blue', 'default' => false,], 
        ];
        
        $this->statusMount = true;
        $this->keyDraw = json_encode($this->acceptKey);
        $this->acceptColor = json_encode($tempColor);
    }
    
    public function saveDraw($data) {
        if (! is_array($data) || ! isset($data['value'])) return;
        
        $objData = json_decode(json_encode($data));
        
        try {
            $checkData = $this->checkDrawData($objData);
            if (! $checkData->status ) throw new \Exception($checkData->message);
            
            $dataValues = $objData->value;
            
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

            $this->dispatch('drawnewsignaturecreate', [
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
            
            $this->dispatch('drawnewsignaturecreate', [
                'status' => false,
                'message' => "Signature submission failed: {$message}"
            ]);
            
            return;
        }
    }
    
    public function render()
    {
        return view('livewire.app.signature.partial.create.draw-signature');
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
