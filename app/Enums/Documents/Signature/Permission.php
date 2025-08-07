<?php

namespace App\Enums\Documents\Signature;

use App\Contracts\Enums\BaseEnumInterface;
use App\Contracts\Enums\HasDefaultEnum;
use App\Trait\InteractWithBaseEnum;

enum Permission: string implements BaseEnumInterface, HasDefaultEnum {
    use InteractWithBaseEnum;
    
    case VALIDATE = 'validate';
    case FINALIZE = 'finalize';
    case SIGN = 'sign';
    
    public static function get_default_case() {
        return static::VALIDATE;
    }
    
    public static function get_default(): string {
        return static::get_default_case()->value;
    }
    
    public static function get_default_name(): string {
        return static::get_default_case()->name;
    }
    
    public static function get_default_label(): string {
        return static::get_default_case()->label();
    }
    
    public function label(): string {
        return ucfirst(strtolower($this->name));
    }
}