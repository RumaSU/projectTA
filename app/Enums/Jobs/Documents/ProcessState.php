<?php

namespace App\Enums\Jobs\Documents;

use App\Contracts\Enums\HasAssociatedModelEnum;
use App\Trait\InteractWithBaseEnum;
use App\Trait\InteractWithModelEnum;

enum ProcessState: string implements HasAssociatedModelEnum {
    use InteractWithBaseEnum, InteractWithModelEnum;
    
    case PROCESSABLE = 'processable';
    case BLOCKED = 'blocked';
    
    public static function model_class_name(): string {
        return \App\Models\Jobs\Documents\Process::class;
    }
    
    public static function get_default(): string {
        return static::PROCESSABLE->value;
    }
    
    public static function get_default_name(): string {
        return static::PROCESSABLE->name;
    }
    
    public function label(): string {
        return match($this) {
            static::PROCESSABLE => static::PROCESSABLE->value,
            static::BLOCKED => static::BLOCKED->value,
        };
    }
}