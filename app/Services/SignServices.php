<?php

namespace App\Services;

use App\Utils\ModelUtils;

use App\Enums\Files\Entity;
use App\Enums\Documents\Signature\Type as DocType;


class SignServices {
    
    protected static string $model = \App\Models\Documents\Document::class; 
    protected static string $model_collab = \App\Models\Documents\DocumentCollaborator::class; 
    protected static string $model_version = \App\Models\Documents\DocumentVersions::class;
    
    public static function can_access(string $id_document, string $id_user) {
        
        if (! ModelUtils::createInstanceModel(\App\Models\Users\User::class)->find($id_user)) {
            return false;
        }
        
        $model = ModelUtils::createInstanceModel(\App\Models\Documents\Document::class);
        // $model_sign = ModelUtils::createInstanceModel(\App\Models\Documents\Signatures::class);
        // $model_signType = ModelUtils::createInstanceModel(\App\Models\Documents\SignaturesType::class);
        
        
        $model_collab = ModelUtils::createInstanceModel(\App\Models\Documents\DocumentCollaborator::class);
        
        $query = $model->query()
            ->where($model->getKeyName(), '=', $id_document)
            ->where('owner_id', '=', $id_user)
            ->where('is_delete', '=', false)
            ;
        
        if ($query->exists()) {
            return true;
        }
        
        
        $query_collab = $model_collab->query()
            ->where($model->getKeyName(), '=', $id_document)
            ->where("id_user", '=', $id_user)
            ;
        
        if ($query_collab->exists()) {
            return true;
        }
        
        return false;
    }
    
    
    public static function get_file(string $id_document) {
        $version = ModelUtils::createInstanceModel(static::$model_version);
        
        $findVersion = $version->query()
            ->where('id_document', '=', $id_document)
            ->latest('version')
            ->first()
            ;
        if (! $findVersion) {
            return null;
        }
        
        $entity = Entity::DOCUMENT;
        $pivotFile = ModelUtils::createInstanceModel($entity->get_class_pivot_file());
        $findPivot = $pivotFile->query()
            ->where($version->getKeyName(), '=', $findVersion->{$version->getKeyName()})
            ->first()
            ;
        
        if (! $findPivot) {
            return null;
        }
        
        $fileEntity = ModelUtils::createInstanceModel($entity->get_class_file());
        $findFileEntity = $fileEntity->query()
            ->find($findPivot->{$fileEntity->getKeyName()});
        
        if (! $findFileEntity) {
            return null;
        }
        
        $fileDiskEntity = ModelUtils::createInstanceModel(\App\Models\Files\DiskEntity::class);
        $findFileDiskEntity = $fileDiskEntity->query()
            ->where('entity_type', '=', $entity->table_file())
            ->where('id_entity', '=', $findFileEntity->{$fileEntity->getKeyName()})
            ->first()
            ;
        if (! $findFileDiskEntity) {
            return null;
        }
        
        $fileDisk = ModelUtils::createInstanceModel(\App\Models\Files\Disk::class);
        $findDisk = $fileDisk->query()
            ->find($findFileDiskEntity->{$fileDisk->getKeyName()})
            ;
        
        if (! $findDisk) {
            return null;
        }
        
        $fileToken = ModelUtils::createInstanceModel(\App\Models\Files\DiskToken::class);
        $findToken = $fileToken->query()
            ->where($fileDiskEntity->getKeyName(), '=', $findFileDiskEntity->getKey())
            ->first()
            ;
        
        if (! $findToken) {
            return null;
        }
        
        
        return [
            $findVersion,
            $findFileEntity,
            $findFileDiskEntity,
            $findDisk,
            $findToken
        ];
    }
    
    public static function is_owner(string $id_document, string $id_user): bool {
        $model = ModelUtils::createInstanceModel(\App\Models\Documents\Document::class);
        
        return $model->query()
            ->where($model->getKeyName(), '=', $id_document)
            ->where('owner_id', '=', $id_user)
            ->exists();
    }
    
    
    
}