<?php

namespace App\Enums\Signatures;

use App\Contracts\Enums\HasAssociatedModelEnum;
use App\Trait\InteractWithModelEnum;
use App\Trait\InteractWithBaseEnum;

enum Variant: string implements HasAssociatedModelEnum {
    use InteractWithBaseEnum, InteractWithModelEnum;
    
    case ORIGINAL = 'original';
    case THUMBNAIL = 'thumbnail';
    case X2 = 'X2';
    
    public static function model_class_name(): string|array {
        return [
            \App\Models\Signatures\SignatureDrawings::class,
            \App\Models\Signatures\SignatureFile::class
        ];
    }
    
    public static function get_default(): string {
        return static::ORIGINAL->value;
    }
    
    public static function get_default_name(): string {
        return static::ORIGINAL->name;
    }
    
    public function label(): string {
        return match($this) {
            static::ORIGINAL => static::ORIGINAL->value,
            static::THUMBNAIL => static::THUMBNAIL->value,
            static::X2 => static::X2->value,
        };
    }
    
}