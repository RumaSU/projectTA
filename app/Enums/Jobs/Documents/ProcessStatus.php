<?php

namespace App\Enums\Jobs\Documents;

use App\Contracts\Enums\HasAssociatedModelEnum;
use App\Trait\InteractWithModelEnum;
use App\Trait\InteractWithBaseEnum;


enum ProcessStatus: string implements HasAssociatedModelEnum {
    use InteractWithBaseEnum, InteractWithModelEnum;
    
    case PROCESS = 'process';
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
    case RETRIED = 'retried';
    case COMPLETED = 'completed';
    
    
    public static function model_class_name(): string {
        return \App\Models\Jobs\Documents\Process::class;
    }
    
    public static function get_default(): string {
        return static::PROCESS->value;
    }
    
    public static function get_default_name(): string {
        return static::PROCESS->name;
    }
    
    public function label(): string {
        return match($this) {
            static::PROCESS => static::PROCESS->value,
            static::SUCCESS => static::SUCCESS->value,
            static::FAILED => static::FAILED->value,
            static::CANCELLED => static::CANCELLED->value,
            static::RETRIED => static::RETRIED->value,
            static::COMPLETED => static::COMPLETED->value,
        };
    }
    
}