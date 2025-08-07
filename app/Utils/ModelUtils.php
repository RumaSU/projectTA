<?php

namespace App\Utils;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use App\Services\Support\UniqueValueGenerator;

use App\Libraries\UuidHelper;
use App\Libraries\ArrayHelper;

use InvalidArgumentException;
use RuntimeException;

class ModelUtils {
    
    
    private const MAP_MODELS = [
        // ---- Users ----
        'user' => [
            'model_class' => \App\Models\Users\User::class,
            'uuid_version' => 'v4'
        ],
        'user_account_status' => [
            'model_class' => \App\Models\Users\UserAccountStatus::class,
            'uuid_version' => 'v4'
        ],
        'user_data' => [
            'model_class' => \App\Models\Users\UserData::class,
            'uuid_version' => null
        ],
        'user_personal' => [
            'model_class' => \App\Models\Users\UserPersonal::class,
            'uuid_version' => null
        ],
        'user_phone' => [
            'model_class' => \App\Models\Users\UserPhone::class,
            'uuid_version' => 'v7'
        ],
        'user_remember_token' => [
            'model_class' => \App\Models\Users\UserRememberToken::class,
            'uuid_version' => null
        ],
        'user_social_media' => [
            'model_class' => \App\Models\Users\UserSocialMedia::class,
            'uuid_version' => 'v7'
        ],
        
        // ---- Signatures ----
        'signature' => [ 
            'model_class' => \App\Models\Signatures\Signature::class,
            'uuid_version' => 'v4'
        ],
        'signature_file' => [ 
            'model_class' => \App\Models\Signatures\SignatureFile::class,
            'uuid_version' => 'v7'
        ],
        'signature_drawing' => [ 
            'model_class' => \App\Models\Signatures\SignatureDrawings::class,
            'uuid_version' => 'v7'
        ],
        'signature_type' => [ 
            'model_class' => \App\Models\Signatures\SignatureType::class,
            'uuid_version' => 'v7'
        ],
        
        // ---- Documents ----
        'document' => [ 
            'model_class' => \App\Models\Documents\Document::class,
            'uuid_version' => 'v4'
        ],
        'document_collaborator' => [ 
            'model_class' => \App\Models\Documents\DocumentCollaborator::class,
            'uuid_version' => 'v7'
        ],
        'document_file' => [ 
            'model_class' => \App\Models\Documents\DocumentFile::class,
            'uuid_version' => 'v7'
        ],
        'document_information' => [ 
            'model_class' => \App\Models\Documents\DocumentInformation::class,
            'uuid_version' => null
        ],
        'document_publicity' => [ 
            'model_class' => \App\Models\Documents\DocumentPublicity::class,
            'uuid_version' => null
        ],
        'document_versions' => [ 
            'model_class' => \App\Models\Documents\DocumentVersions::class,
            'uuid_version' => 'v7'
        ],
        'document_signatures' => [ 
            'model_class' => \App\Models\Documents\Signatures::class,
            'uuid_version' => 'v4'
        ],
        'document_signatures_permission' => [ 
            'model_class' => \App\Models\Documents\SignaturesPermission::class,
            'uuid_version' => 'v7'
        ],
        'document_signatures_signer' => [ 
            'model_class' => \App\Models\Documents\SignaturesSigner::class,
            'uuid_version' => 'v7'
        ],

        'document_signatures_flow' => [ 
            'model_class' => \App\Models\Documents\SignaturesFlow::class,
            'uuid_version' => null
        ],
        'document_signatures_status' => [ 
            'model_class' => \App\Models\Documents\SignaturesStatus::class,
            'uuid_version' => null
        ],
        'document_signatures_type' => [ 
            'model_class' => \App\Models\Documents\SignaturesType::class,
            'uuid_version' => null
        ],
        
        
        
        'file_disk' => [
            'model_class' => \App\Models\Files\Disk::class,
            'uuid_version' => 'v7'
        ],
        'file_disk_entity' => [
            'model_class' => \App\Models\Files\DiskEntity::class,
            'uuid_version' => 'v7'
        ],
        'file_disk_token' => [
            'model_class' => \App\Models\Files\DiskToken::class,
            'uuid_version' => 'v7'
        ],
        
        'file_signature' => [
            'model_class' => \App\Models\Files\Entity\Signatures::class,
            'uuid_version' => 'v7'
        ],
        
        
        
        'app_jobs_process_docs' => [ 
            'model_class' => \App\Models\Jobs\Documents\Process::class,
            'uuid_version' => 'v7'
        ],
        
    ];
    
    /**
     * Retrieves model information from the MAP_MODELS constant.
     * @param string $key
     * @return array|null
     */
    public static function getModelInfo(string $key): ?array {
        return static::MAP_MODELS[$key] ?? null;
    }
    /**
     * Summary of getModelInfoPrefix
     * @param string $prefix
     * @return array
     */
    public static function getModelInfoPrefix(string $prefix = ''): array {
        return collect(static::MAP_MODELS)
            ->filter(fn($v, $k) => str_contains($k, $prefix))
            ->all();
    }
    
    public static function generateNewUuid(?string $key_model = null): string {
        if ($key_model === null) {
            return (string) UuidHelper::generate();
        }
        if (! static::checkModel($key_model)) {
            throw new InvalidArgumentException("Model key '{$key_model}' not found in document models for UUID generation.");
        }
        
        $modelInstance = static::createInstanceModel($key_model);
        if (! $modelInstance->getKeyName()) {
            throw new RuntimeException("Model '{$key_model}' does not have a primary key configured.");
        }
        if (! SchemaUtils::checkColumn($modelInstance->getTable(), $modelInstance->getKeyName(), $modelInstance->getConnectionName())) {
            throw new RuntimeException("Model '{$key_model}' does not have a primary key configured.");
        }
        if ($modelInstance->getKeyType() !== 'uuid' || 
            $modelInstance->incrementing) {
            
            throw new RuntimeException("Model '{$key_model}' primary key type is not 'uuid' and is incrementing. Cannot generate unique UUID for this model.");
        }
        
        $modelInfo = static::getModelInfo($key_model);
        $uuid_version = $modelInfo['uuid_version'] ?? 'v4';
        
        return UniqueValueGenerator::uuid(
            $uuid_version,
            $modelInstance->getKeyName(),
            $modelInstance->getTable(),
            $modelInstance->getConnectionName(),
        );
        
    }
    
    /**
     * Checks if a model key exists in MAP_MODELS.
     * @param string $key
     * @return bool
     */
    public static function checkModel(string $key): bool {
        return (class_exists($key) && (new $key) instanceof Model) || array_key_exists($key, static::MAP_MODELS);
    }
    
    /**
     * Creates and returns an Eloquent model instance.
     * @param string $modelKey
     * @return Model
     * @throws \RuntimeException
     */
    public static function createInstanceModel(string $modelKey, bool $onlyRegistered = false): Model {
        if (!$onlyRegistered && class_exists($modelKey) && (new $modelKey)  instanceof Model) {
            return new $modelKey;
        }
        
        $modelInfo = static::getModelInfo($modelKey);
        if ($modelInfo === null) {
            throw new RuntimeException("Model info not found for key '{$modelKey}'.");
        }
        
        $modelClass = $modelInfo['model_class'];
        if (! class_exists($modelClass)) {
            throw new RuntimeException("Model class '{$modelClass}' not found for key '{$modelKey}'.");
        }
        return new $modelClass;
    }
    
    /**
     * Creates and returns an Eloquent Query Builder instance.
     * @template TModel of \Illuminate\Database\Eloquent\Model
     * 
     * @param string $modelKey
     * @param class-string<TModel>|string $modelKey
     * 
     * @return Builder<TModel>
     * @throws RuntimeException
     */
    public static function createInstanceQuery(string $modelKey): Builder {
        $modelInstance = static::createInstanceModel($modelKey);
        return $modelInstance->query();
    }
    
    /**
     * Validates if input values are allowed by the model's fillable property.
     * Prevents mass assignment vulnerabilities.
     * @param string $modelKey
     * @param array|string $values
     * @return bool True if all keys are valid, false otherwise.
     * @throws RuntimeException If model instance creation fails.
     */
    public static function checkFillable(string $modelKey, array|string $values): bool {
        $modelInstance = static::createInstanceModel($modelKey);
        $fillableKeys = $modelInstance->getFillable();
        
        if (! is_array($values)) {
            return in_array($values, $fillableKeys);
        }
        
        $inputKeys = ArrayHelper::is_list($values)
            ? $values
            : array_keys($values);
        
        foreach ($inputKeys as $key) {
            if (!in_array($key, $fillableKeys)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Summary of create
     * @param string $modelKey
     * @param array $values
     * @throws InvalidArgumentException If values are empty or not fillable.
     * @throws RuntimeException If model or query issues occur.
     * @return Model
     */
    public static function create(string $modelKey, array $values): Model {
        if (empty($values)) {
            throw new InvalidArgumentException("Values for create operation cannot be empty.");
        }
        
        if (!static::checkFillable($modelKey, $values)) {
            throw new InvalidArgumentException("One or more provided values are not fillable for model '{$modelKey}'.");
        }
        
        $modelQuery = static::createInstanceQuery($modelKey);
        return $modelQuery->create($values);
    }
    
}