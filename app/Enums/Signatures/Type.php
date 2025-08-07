<?php

namespace App\Enums\Signatures;

use App\Contracts\Enums\HasAssociatedModelEnum;
use App\Trait\InteractWithModelEnum;
use App\Trait\InteractWithBaseEnum;

enum Type: string implements HasAssociatedModelEnum {
    use InteractWithBaseEnum, InteractWithModelEnum;
    
    case SIGNATURE = 'signature';
    case PARAF = 'paraf';
    
    
    
    public static function model_class_name(): string {
        return \App\Models\Signatures\SignatureType::class;
    }
    
    public static function get_default(): string {
        return static::SIGNATURE->value;
    }
    
    public static function get_default_name(): string {
        return static::SIGNATURE->label();
    }
    
    public function label(): string {
        return match($this) {
            static::SIGNATURE => ucfirst(strtolower(static::SIGNATURE->name)),
            static::PARAF => ucfirst(strtolower(static::PARAF->name)),
        };
    }
    
}