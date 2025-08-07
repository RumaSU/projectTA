<?php

namespace App\Services\Support;

use App\Utils\ModelUtils;
use App\Utils\FileUtils;
use App\Utils\StorageUtils;

use App\Enums\Hash;
use App\Enums\Files\Entity;
use App\Services\Support\UniqueValueGenerator;
use App\Utils\LogUtils;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

class FileDiskSupport {
    
    protected string $disk;
    protected string $path;
    
    protected ?string $id_file_disk = null;
    protected ?string $id_file_disk_entity = null;
    protected ?string $id_file_disk_token = null;
    protected ?string $token = null;
    
    protected bool $is_move_success = false;
    protected bool $is_created = false;
    protected bool $is_created_entity = false;
    protected bool $is_created_token = false;
    
    protected string $model_class = \App\Models\Files\Disk::class;
    protected string $model_class_entity = \App\Models\Files\DiskEntity::class;
    protected string $model_class_token = \App\Models\Files\DiskToken::class;
    
    
    public static function clearFileDisk(string $id_file_disk) {
        $instance = new static;
        
        $model = ModelUtils::createInstanceModel($instance->model_class);
        $modelEntity = ModelUtils::createInstanceModel($instance->model_class_entity);
        $modelToken = ModelUtils::createInstanceModel($instance->model_class_token);
        
        $file_disk = $model->query()->find($id_file_disk);
        if (! $file_disk) {
            return;
        }
        
        StorageUtils::delete($file_disk->disk, $file_disk->path);
        
        $file_disk_entity = $modelEntity
            ->query()
            ->where($file_disk->getKeyName(), '=', $file_disk->getKey())
            ->first();
        
        
        if ($file_disk_entity) {
            $modelToken->query()
                ->where('id_file_disk_entity', $file_disk_entity->getKey())
                ->forceDelete();
            
            $entity = Entity::from_table_file($file_disk_entity->entity_type);
            if ($entity) {
                ModelUtils::createInstanceQuery($entity->get_class_file())
                    ->find($file_disk_entity->id_entity)
                    ?->forceDelete();
            }
            
            $file_disk_entity->forceDelete();
        }
        
        $file_disk->forceDelete();
        
    }
    
    
    public static function fromDiskPath(string $disk, string $path): static {
        $instance = new static;
        $instance->disk = $disk;
        $instance->path = $path;
        
        return $instance;
    }
    
    public static function fromId(string $id_file_disk): static {
        $instance = new static;
        $instance->id_file_disk = $id_file_disk;
        
        return $instance->load_file_disk();
    }
    
    public function load_file_disk() {
        $modelClass = ModelUtils::createInstanceQuery($this->model_class)
            ->find($this->id_file_disk);
        
        $this->is_created = $modelClass !== null;
        
        if ($this->is_created) {
            $modelEntity = ModelUtils::createInstanceQuery($this->model_class_entity)
                ->where($modelClass->getKeyName(), '=', $this->id_file_disk)
                ->first();
            
            $this->is_created_entity = $modelEntity !== null;
            
            if ($this->is_created_entity) {
                $this->id_file_disk_entity = $modelEntity->getKey();
            }
        }
        
        return $this;
    }
    
    // public function __construct(string $disk, string $path) {
        
    //     $this->disk = $disk;
    //     $this->path = $path;
        
    // } 
    
    public function can_create_entity(): bool {
        return $this->is_created && 
            ! $this->is_created_entity && 
            $this->id_file_disk;
    }
     
    public function can_create_token(): bool {
        return $this->is_created && 
            $this->is_created_entity && 
            ! $this->is_created_token &&
            $this->id_file_disk &&
            $this->id_file_disk_entity &&
            ! $this->id_file_disk_token;
    }
    
    
    public function exists() {
        return StorageUtils::resolvePath($this->disk, $this->path) !== null;
    }
    
    
    public function move(string $disk, string $path) {
        $this->is_move_success = false;
        
        if (! $this->exists()) {
            return $this;
        }
        
        $storage = StorageUtils::copy($this->disk, $this->path)
            ->to($disk, $path);
        
        if (! $storage->is_success()) {
            return $this;
        }
        
        $storage->withDelete();
        
        $this->disk = $disk;
        $this->path = $path;
        $this->is_move_success = true;
        
        return $this;
    }
    
    
    public function create(): ?string {
        $this->is_created = false;
        
        if (! $this->exists()) {
            return null;
        }
        
        if (! StorageUtils::is_file($this->disk, $this->path)) {
            return null;
        }
        
        $path = StorageUtils::resolvePath($this->disk, $this->path);
        
        $metadata = FileUtils::handle($path)->getMetadata();
        if ($metadata === null) {
            return null;
        }
        
        $uuid = ModelUtils::generateNewUuid($this->model_class);
        $payload = [
            'id_file_disk' => $uuid,
            'disk' => $this->disk,
            'path' => $this->path,
            'file_name' => pathinfo($metadata['filename'], PATHINFO_FILENAME),
            'extension' => $metadata['extension'],
            'mime_type' => $metadata['mime'],
            'size_byte' => $metadata['size'],
        ];
        
        $hashEnum = Hash::get_default_case();
        if (! $this->add_hash_row($payload, $hashEnum)) {
            return null;
        }
        
        $hashFile = $hashEnum->hash_file($path);
        if (! $hashFile) {
            return null;
        }
        
        $payload['hash_file'] = $hashFile;
        $payload['status'] = true;
        $payload['last_check'] = Carbon::now();
        
        ModelUtils::create($this->model_class, $payload);
        
        $this->is_created = true;
        $this->id_file_disk = $uuid;
        
        return $uuid;
    }
    
    
    public function create_entity(string $owner_id, Entity $entity, string $id_entity, string $file_client_name) {
        if (! $this->can_create_entity()) {
            return null;
        }
        LogUtils::log(
            'single', 
            'success validate can create entity', 
            [
            ], 
            'info', null, false
        );
        
        if (! ModelUtils::createInstanceQuery('user')->find($owner_id)) {
            return null;
        }
        LogUtils::log(
            'single', 
            'success validate user found', 
            [
            ], 
            'info', null, false
        );
        
        if (! $entity->get_model_file_by_id($id_entity)) {
            return null;
        }
        LogUtils::log(
            'single', 
            'success validate model file entity found', 
            [
            ], 
            'info', null, false
        );
        
        $uuid = ModelUtils::generateNewUuid($this->model_class_entity);
        $payload = [
            'id_file_disk_entity' => $uuid,
            'id_file_disk' => $this->id_file_disk,
            'owner_id' => $owner_id,
            'entity_type' => $entity->table_file(),
            'id_entity' => $id_entity,
            'file_client_name' => $file_client_name,            
        ];
        if (! $this->add_hash_row($payload, Hash::get_default_case())) {
            return null;
        }
        
        ModelUtils::create($this->model_class_entity, $payload);
        
        LogUtils::log(
            'single', 
            'success store file disk entity', 
            [
            ], 
            'info', null, false
        );
        
        $this->is_created_entity = true;
        $this->id_file_disk_entity = $uuid;

        return $uuid;
    }
    
    
    public function create_token(string $shared_user_id, ?int $expired_days = null): ?array {
        if (! $this->can_create_token()) {
            return null;
        }
        
        if (! ModelUtils::createInstanceQuery('user')->find($shared_user_id)->exists()) {
            return null;
        }
        
        $model = ModelUtils::createInstanceModel($this->model_class_token);
        $uuid = ModelUtils::generateNewUuid($this->model_class_token);
        $token = UniqueValueGenerator::stringByIlluminate(
            32,
            'token',
            $model->getTable(),
            $model->getConnectionName()
        );
        $payload = [
            'id_file_disk_token' => $uuid,
            'id_file_disk_entity' => $this->id_file_disk_entity,
            'shared_user_id' => $shared_user_id,
            'token' => $token,
        ];
        if (! $this->add_hash_row($payload, Hash::get_default_case())) {
            return null;
        }
        if ($expired_days) {
            $payload['expired_at'] = Carbon::now()->addDays($expired_days);
        }
        
        ModelUtils::create($this->model_class_token, $payload);
        
        $this->token = $token;
        $this->id_file_disk_token = $uuid;
        $this->is_created_token = true;
        
        return [
            'token' => $token,
            'uuid' => $uuid
        ];
        
    }
    
    
    public function add_hash_row(array &$payload, Hash $hashEnum) {
        ksort($payload);
        $json = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            return false;
        }
        
        $payload['hash_row'] = $hashEnum->hash($json);
        $payload['hash_type'] = $hashEnum->value;
        
        return true;
    }
    
    
    
    
    
}