<?php

namespace App\Services\Http\Files;

use App\Enums\Documents\Publicity;
use App\Enums\Files\Entity;
use App\Utils\LogUtils;
use App\Utils\RequestUtils;
use App\Utils\ModelUtils;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class View {
    
    protected string $token;
    
    
    protected string $model = \App\Models\Files\Disk::class;
    protected string $model_entity = \App\Models\Files\DiskEntity::class;
    protected string $model_token = \App\Models\Files\DiskToken::class;
    
    
    public function __construct(string $token) {
        $this->token = $token;
    }
    
    public static function construct(string $token) {
        return (new static($token));
    }
    
    public static function handleCanAccessWithAUth(string $token, Entity $entity) {
        return (new static($token))->can_access_with_auth($entity);
    }
    
    public static function handleGetFileEntity(string $token) {
        return (new static($token))->get_file_entity();
    }
    
    public function get_file_entity() {
        
        $modelEntity = ModelUtils::createInstanceModel($this->model_entity);
        $findToken = ModelUtils::createInstanceQuery($this->model_token)
            ->where('token', '=', $this->token)
            ->first();
        
        if (! $findToken) {
            return false;
        }
        
        $findEntity = $modelEntity->query()
            ->where($modelEntity->getKeyName(), '=', $findToken->{$modelEntity->getKeyName()})
            ->first();
        
        
        if (! $findEntity) {
            return false;
        }
        
        $entityEnum = Entity::from_table_file($findEntity->entity_type);
        if (! $entityEnum) {
            return false;
        }
        
        return $findEntity;
    }
    
    public function can_access_with_auth(Entity $entity) {
        
        $modelToken = ModelUtils::createInstanceQuery($this->model_token)
            ->where('token', '=', $this->token)
            ->first();
        
        $result = [
            'status' => false,
            'code' => 403,
            'message' => 'Forbidden access'
        ];
        
        if (! $modelToken) {
            $result['code'] = 404;
            $result['message'] = 'Invalid access token';
            
            return $result;
        }
        
        $is_expired = Carbon::parse($modelToken->expired_at)->lte(now());
        if ($modelToken->is_expired || ($modelToken->expired_at && $is_expired)) {
            $result['code'] = 410;
            $result['message'] = 'This access link has expired';
            
            return $result;
        }
        
        $fileDiskEntity = $this->get_file_entity();
        if ($fileDiskEntity === false) {
            return $result;
        }
        $entity_type = Entity::from_table_file($fileDiskEntity->entity_type);
        if (! $entity_type || $entity_type !== $entity) {
            $result['message'] = 'Access denied';
            return $result;
        }
        
        if ($entity === Entity::SIGNATURE) {
            
            if (Auth::check()) {
                
                if ($modelToken->shared_user_id && $modelToken->shared_user_id !== Auth::user()->id_user) {
                    $result['code'] = 403;
                    $result['message'] = 'Access denied';
                    
                    return $result;
                }
                
            } else {
                $result['message'] = 'Unauthorized access';
                return $result;
            }
            
        } else if ($entity === Entity::DOCUMENT) {
            
            $model = ModelUtils::createInstanceModel($entity->get_class_root());
            $modelCollab = ModelUtils::createInstanceModel(\App\Models\Documents\DocumentCollaborator::class);
            $modelPublicity = ModelUtils::createInstanceModel(\App\Models\Documents\DocumentPublicity::class);
            $modelVersion = ModelUtils::createInstanceModel(\App\Models\Documents\DocumentVersions::class);
            $modelPivotFile = ModelUtils::createInstanceModel(\App\Models\Documents\DocumentFile::class);
            
            
            $modelFile = ModelUtils::createInstanceModel(Entity::DOCUMENT->get_class_file());
            $findFile = $modelFile
                ->find($fileDiskEntity->id_entity)
                // ->where("{$modelDisk->getKeyName()}", '=', "{ $fileDiskEntity->{$modelDisk->getKeyName()} }")
                // ->first()   
                ;
            
            if (! $findFile) {
                $result['message'] = 'Access denied';
                return $result;
            }
            
            $findPivot = $modelPivotFile->query()
                ->where($modelFile->getKeyName(), '=', $findFile->{$modelFile->getKeyName()})
                ->first()
                ;
                
            if (! $findPivot) {
                $result['message'] = 'Access denied';
                return $result;
            }
            
            $findVersion = $modelVersion->query()
                ->find($findPivot->{$modelVersion->getKeyName()})
                ;
                
            if (! $findVersion) {
                $result['message'] = 'Access denied aaaaaaaaaaaa';
                return $result;
            }
            
            $findPublicity = $modelPublicity->query()
                ->where($model->getKeyName(), '=', $findVersion->{$model->getKeyName()})
                ->first()
                ;
            
            if ($findPublicity && $findPublicity->status_publicity === Publicity::PUBLIC->value) {
                $result['status'] = true;
                $result['code'] = 200;
                $result['message'] = null;
                
                return $result;
            }
            
            if (Auth::check()) {
                
                if ($modelToken->shared_user_id && $modelToken->shared_user_id === Auth::user()->id_user) {
                    $result['status'] = true;
                    $result['code'] = 200;
                    $result['message'] = null;
                    
                    return $result;
                }
                
                $findOwner = $model->query()
                    ->find($findVersion->{$model->getKeyName()});
                
                if ($findOwner && $findOwner->owner_id === Auth::user()->id_user) {
                    $result['status'] = true;
                    $result['code'] = 200;
                    $result['message'] = null;
                    
                    return $result;
                }
                
                
                
                $findCollab = $modelCollab->query()
                    ->where($model->getKeyName(), '=', $findVersion->{$model->getKeyName()})
                    ->where('id_user', '=', Auth::user()->id_user)
                    ->first()
                    ;
                if (! $findCollab) {
                    $result['message'] = 'Unauthorized access';
                    return $result;
                }
                
            } else {
                $result['message'] = 'Unauthorized access';
                return $result;
            }
            
        } else {
            
            $result['code'] = 422;
            $result['message'] = 'Forbidden access, not supported';
            return $result;
            
        }
        
        
        return [
            'status' => true,
            'code' => 200,
            'message' => null
        ];
        
    }
    
}