<?php

namespace App\Enums\Audit\Documents;

use App\Contracts\Enums\BaseEnumInterface;
use App\Trait\InteractWithBaseEnum;

enum Event: string implements BaseEnumInterface {
    use InteractWithBaseEnum;
    case CREATED = 'created';
    case UPDATED = 'updated';
    case MODIFIED = 'modified';
    case ARCHIVED = 'archived';
    case STATUS_CHANGED = 'status_changed';
    case SIGNED = 'signed';
    case SIGNED_REQUEST = 'signed_request';
    case FINALIZE = 'finalize';
    
    
    
    
    public function label(): string {
        return ucfirst(strtolower($this->name));
    }
    
    public function icon() {
        return match($this) {
            static::CREATED => 'fas fa-plus',
            static::UPDATED => 'fas fa-circle-arrow-up',
            static::MODIFIED => 'fas fa-pen-to-square',
            static::ARCHIVED => 'fas fa-box-archive',
            static::STATUS_CHANGED => 'fas fa-arrows-spin',
            static::SIGNED => 'fas fa-signature',
            static::SIGNED_REQUEST => 'fas fa-file-signature',
            static::FINALIZE => 'fas fa-flag-checkered',
        };
    }
    
}