<?php

namespace App\Services\Support\Documents\Enum;

use App\Services\Support\Trait\EnumJobProcessDocuments;
use Illuminate\Support\Collection;

enum JobProcessType: string {
    use EnumJobProcessDocuments;
    
    
    case CREATE = 'create';
    case DELETE = 'delete';
    case UPDATE = 'update';
    
    case CREATE_BULK = 'create_bulk';
    case DELETE_BULK = 'delete_bulk';
    case UPDATE_BULK = 'update_bulk';
    
    case IMPORT = 'import';
    case EXPORT = 'export';
    case SYNC = 'sync';
    case MIGRATE = 'migrate';
    case GENERATE = 'generate';
    case VALIDATE = 'validate';
    case ARCHIVE = 'archive';
    case RESTORE = 'restore';
    case SIGN_DOCUMENT = 'sign_document';
    case VERIFY_SIGNATURE = 'verify_signature';
    
    
    
    
    public static function type_default(): string {
        return static::CREATE->value;
    }
    
    public static function get_type(bool $to_array = false): array|Collection {
        $collection = static::get_cases();
        
        return $to_array
            ? $collection->toArray()
            : $collection;
    }
    
    public static function get_type_map(): array {
        return static::get_type()
            ->pluck('value', 'name')
            ->toArray();
    }
    
    public static function isValidType(string $type): bool {
        return in_array($type, static::get_type_map());
    }
    
    public static function getModelType(string $id): string|null {
        return static::getModelById($id)?->type;
    }
    
}