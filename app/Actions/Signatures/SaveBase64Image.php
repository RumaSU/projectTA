<?php

namespace App\Actions\Signatures;

use App\Enums\Signatures\Variant;

use App\Libraries\ArrayHelper;

use App\Utils\ModelUtils;

class SaveBase64Image {
    
    protected array $images;
    protected array $imagesInfo = [];
    protected string $uuid;
    
    protected const IMAGE_ITEM = [
        'variant', 'value'
    ];
    
    public function __construct(array $images, string|null $uuid) {
        $this->images = $images;
        $this->uuid = $uuid; 
    }
    
    
    public function check(): bool {
        if (! ArrayHelper::is_list($this->images)) {
            return false;
        }
        
        foreach($this->images as $image) {
            
            if (! ArrayHelper::key_exists(static::IMAGE_ITEM, $image)) {
                return false;
            }
            
            if (! Variant::is_valid($image['variant'])) {
                return false;
            }
            
            
            
        }
        
        
        return true;
    }
    
    
    
    
}