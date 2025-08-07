<?php

namespace App\Enums\Audit\Documents;

use App\Contracts\Enums\BaseEnumInterface;
use App\Trait\InteractWithBaseEnum;

enum Event: string implements BaseEnumInterface {
    use InteractWithBaseEnum;
    case CREATED = 'created';
    case UPDATED = 'updated';
    case ARCHIVED = 'archived';
    case STATUS_CHANGED = 'status_changed';
    case SIGNED = 'SIGNED';
    
    
    
    
    public function label(): string {
        return ucfirst(strtolower($this->name));
    }
    
}