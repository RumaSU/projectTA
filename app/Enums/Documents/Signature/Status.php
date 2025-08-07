<?php

namespace App\Enums\Documents\Signature;

use App\Contracts\Enums\BaseEnumInterface;
use App\Contracts\Enums\HasDefaultEnum;
use App\Trait\InteractWithBaseEnum;

enum Status: string implements BaseEnumInterface, HasDefaultEnum {
    use InteractWithBaseEnum;
    
    case DRAFT = 'draft';
    case PROGRESS = 'progress';
    case COMPLETED = 'completed';
    case REJECTED = 'rejected';
    
    public static function get_default_case() {
        return static::DRAFT;
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
    
    public function get_style() {
        return match($this) {
            static::PROGRESS => [
                'background' => 'bg-blue-100',
                'textColor' => 'text-blue-800',
                'text' => 'In Progress'
            ],
            static::COMPLETED => [
                'background' => 'bg-green-100',
                'textColor' => 'text-green-800',
                'text' => 'Completed'
            ],
            
            static::REJECTED => [
                'background' => 'bg-red-100',
                'textColor' => 'text-red-800',
                'text' => 'Rejected',
            ],
            static::DRAFT => [
                'background' => 'bg-gray-200',
                'textColor' => 'text-gray-800',
                'text' => 'Draft',
            ],
            
        };
    }
    
}