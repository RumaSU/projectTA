<?php

namespace App\Services\Support\Documents\Enum;

use App\Services\Support\Trait\EnumJobProcessDocuments;
use Illuminate\Support\Collection;

enum JobProcessStatus: string {
    use EnumJobProcessDocuments;
    
    case PROCESS = 'process';
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
    case RETRIED = 'retried';
    case COMPLETED = 'completed';
    
    
    public static function status_default(): string {
        return static::PROCESS->value;
    }
    
    public static function get_status(bool $to_array = false): array|Collection {
        $collection = static::get_cases();
        
        return $to_array
            ? $collection->toArray()
            : $collection;
    }
    
    public static function get_status_map(): array {
        return static::get_status()
            ->pluck('value', 'name')
            ->toArray();
    }
    
    public static function isValidStatus(string $status): bool {
        return in_array($status, static::get_status_map());
    }
    
    public static function getModelStatus(string $id): string|null {
        return static::getModelById($id)?->status;
    }
}