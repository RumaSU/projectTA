<?php

namespace App\Enums\Audit\Documents;

use App\Contracts\Enums\BaseEnumInterface;
use App\Trait\InteractWithBaseEnum;

enum Category: string implements BaseEnumInterface {
    use InteractWithBaseEnum;
    
    case DOCUMENT = 'document';
    case SIGNATURE = 'signature';
    case AUDIT = 'audit';
    
    public function label(): string {
        return ucfirst(strtolower($this->name));
    }
    
}