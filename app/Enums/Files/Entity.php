<?php

namespace App\Enums\Files;

use App\Contracts\Enums\BaseEnumInterface;
use App\Trait\InteractWithBaseEnum;

use App\Utils\ModelUtils;

use App\Enums\Config\Filesystem as EnumFilesystem;

use Illuminate\Database\Eloquent\Model;

enum Entity: string implements BaseEnumInterface {
    use InteractWithBaseEnum;
    
    
    case DOCUMENT = 'document';
    case SIGNATURE = 'signature';
    
    
    public static function from_table_root(string $table) {
        return match($table) {
            static::DOCUMENT->table_root() => static::DOCUMENT,
            static::SIGNATURE->table_root() => static::SIGNATURE,
        };
    }
    
    public static function from_table_file(string $table) {
        return match($table) {
            static::DOCUMENT->table_file() => static::DOCUMENT,
            static::SIGNATURE->table_file() => static::SIGNATURE,
        };
    }
    
    
    public function label(): string {
        return ucfirst(strtolower($this->name));
    }
    
    public function get_class_root(): string {
        return match($this) {
            static::DOCUMENT => \App\Models\Documents\Document::class,
            static::SIGNATURE => \App\Models\Signatures\Signature::class
        };
    }
    
    public function get_class_file(): string {
        return match($this) {
            static::DOCUMENT => \App\Models\Files\Entity\Documents::class,
            static::SIGNATURE => \App\Models\Files\Entity\Signatures::class
        };
    }
    
    public function get_class_pivot_file() {
        return match($this) {
            static::DOCUMENT => \App\Models\Documents\DocumentFile::class,
            static::SIGNATURE => \App\Models\Signatures\SignatureFile::class
        };
    }
    
    public function get_filesystem() {
        return match($this) {
            static::DOCUMENT => EnumFilesystem::DOCUMENTS,
            static::SIGNATURE => EnumFilesystem::SIGNATURES
        };
    }
    
    public function get_disk(): string {
        return $this->get_filesystem()->value;
    }
    
    public function instance_model_root(): Model {
        return ModelUtils::createInstanceModel($this->get_class_root());
    }
    
    public function instance_model_file(): Model {
        return ModelUtils::createInstanceModel($this->get_class_file());
    }
    
    public function instance_model_pivot_file(): Model {
        return ModelUtils::createInstanceModel($this->get_class_pivot_file());
    }
    
    
    
    public function get_model_root_by_id(string|int $id) {
        return $this->instance_model_root()->query()->find($id);
    }
    
    public function get_model_file_by_id(string|int $id) {
        return $this->instance_model_file()->query()->find($id);
    }
    
    
    
    public function table_root(): string {
        return $this->instance_model_root()->getTable();
    }
    
    public function table_file(): string {
        return $this->instance_model_file()->getTable();
    }
    
    public function table_pivot() {
        return $this->instance_model_pivot_file()->getTable();
    }
    
}