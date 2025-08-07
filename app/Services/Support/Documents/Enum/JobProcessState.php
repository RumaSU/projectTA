<?php

namespace App\Services\Support\Documents\Enum;

use App\Services\Support\Trait\EnumJobProcessDocuments;
use Illuminate\Support\Collection;

enum JobProcessState: string {
    use EnumJobProcessDocuments;
    
    case PROCESSABLE = 'processable';
    case BLOCKED = 'blocked';
    
    
    public static function state_default(): string {
        return static::PROCESSABLE->value;
    }
    
    public static function get_state(bool $to_array = false): array|Collection {
        $collection = static::get_cases();
        
        return $to_array
            ? $collection->toArray()
            : $collection;
    }
    
    public static function get_state_map(): array {
        return static::get_state()
            ->pluck('value', 'name')
            ->toArray();
    }
    
    public static function isValidState(string $state): bool {
        return in_array($state, static::get_state_map());
    }
    
    public static function getModelState(string $id): string|null {
        return static::getModelById($id)?->process_state;
    }
}