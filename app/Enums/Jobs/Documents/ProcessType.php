<?php

namespace App\Enums\Jobs\Documents;

use App\Contracts\Enums\HasAssociatedModelEnum;
use App\Trait\InteractWithModelEnum;
use App\Trait\InteractWithBaseEnum;

use App\Services\Support\Trait\EnumJobProcessDocuments;
use Illuminate\Support\Collection;

enum ProcessType: string implements HasAssociatedModelEnum {
    use InteractWithBaseEnum, InteractWithModelEnum;
    
    
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
    
    public static function model_class_name(): string {
        return \App\Models\Jobs\Documents\Process::class;
    }
    
    public static function get_default(): string {
        return static::CREATE->value;
    }
    
    public static function get_default_name(): string {
        return static::CREATE->name;
    }
    
    public function label(): string {
        return match($this) {
            static::CREATE => static::CREATE->value,
            static::DELETE => static::DELETE->value,
            static::UPDATE => static::UPDATE->value,
            static::CREATE_BULK => static::CREATE_BULK->value,
            static::DELETE_BULK => static::DELETE_BULK->value,
            static::UPDATE_BULK => static::UPDATE_BULK->value,
            static::IMPORT => static::IMPORT->value,
            static::EXPORT => static::EXPORT->value,
            static::SYNC => static::SYNC->value,
            static::MIGRATE => static::MIGRATE->value,
            static::GENERATE => static::GENERATE->value,
            static::VALIDATE => static::VALIDATE->value,
            static::ARCHIVE => static::ARCHIVE->value,
            static::RESTORE => static::RESTORE->value,
            static::SIGN_DOCUMENT => static::SIGN_DOCUMENT->value,
            static::VERIFY_SIGNATURE => static::VERIFY_SIGNATURE->value,
        };
    }
    
}