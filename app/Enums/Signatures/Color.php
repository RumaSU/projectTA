<?php

namespace App\Enums\Signatures;

use App\Contracts\Enums\HasAssociatedModelEnum;
use App\Trait\InteractWithModelEnum;
use App\Trait\InteractWithBaseEnum;

enum Color: string implements HasAssociatedModelEnum {
    use InteractWithBaseEnum, InteractWithModelEnum;

    // ['color' => '#000000', 'text' => 'Black', 'default' => true,], 
    // ['color' => '#ff0000', 'text' => 'Red', 'default' => false,], 
    // ['color' => '#00ff00', 'text' => 'Green', 'default' => false,], 
    // ['color' => '#0000ff', 'text' => 'Blue', 'default' => false,], 

    case BLACK = '#000000';
    case RED = '#ff0000';
    case GREEN = '#00ff00';
    case BLUE = '#0000ff';
    
    
    public static function model_class_name(): string {
        return \App\Models\Signatures\SignatureDrawings::class;
    }
    
    public static function get_default(): string {
        return static::BLACK->value;
    }
    
    public static function get_default_name(): string {
        return static::BLACK->label();
    }
    
    public static function get_mapped_colors() {
        
        $mapped = [];
        foreach (static::cases() as $case) {
            $mapped[] = [
                'color' => $case->value,
                'text' => $case->label(),
                'default' => $case->value === static::get_default()
            ];
        }
        
        return $mapped;
    }
    
    public function label(): string {
        return match($this) {
            static::BLACK => ucfirst(strtolower(static::BLACK->name)),
            static::RED => ucfirst(strtolower(static::RED->name)),
            static::GREEN => ucfirst(strtolower(static::GREEN->name)),
            static::BLUE => ucfirst(strtolower(static::BLUE->name)),
        };
    }
    
}