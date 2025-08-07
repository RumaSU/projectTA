<?php

namespace App\Enums;

use App\Contracts\Enums\BaseEnumInterface;
use App\Contracts\Enums\HasDefaultEnum;
use App\Trait\InteractWithBaseEnum;

enum CustomToastNotification: string implements BaseEnumInterface, HasDefaultEnum {
    use InteractWithBaseEnum;
    case INFO = 'info';
    case SUCCESS = 'success';
    case WARNING = 'warning';
    case DANGER = 'danger';
    
    
    public static function get_dispatch_name() {
        return 'customnotify';
    }
    
    public static function get_default(): string {
        return static::INFO->value;
    }
    
    public static function get_default_name(): string {
        return static::INFO->label();
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
    
    public static function get_default_sender() {
        return 'system';
    }
    
    public function label(): string {
        return ucfirst(strtolower($this->name));
    }
    
}