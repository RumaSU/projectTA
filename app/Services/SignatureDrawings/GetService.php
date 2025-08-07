<?php

namespace App\Services\SignatureDrawings;

use App\Enums\Signatures\Type;
use App\Enums\Signatures\Variant;
use App\Utils\ModelUtils;

class GetService {
    
    protected string $model_user = \App\Models\Users\User::class;
    protected string $model = \App\Models\Signatures\Signature::class;
    protected string $model_file = \App\Models\Signatures\SignatureFile::class;
    protected string $model_type = \App\Models\Signatures\SignatureType::class;
    protected string $model_drawing = \App\Models\Signatures\SignatureDrawings::class;
    protected string $model_file_signature = \App\Models\Files\Entity\Signatures::class;
    protected string $model_file_disk = \App\Models\Files\Disk::class;
    protected string $model_file_disk_entity = \App\Models\Files\DiskEntity::class;
    protected string $model_file_disk_token = \App\Models\Files\DiskToken::class;
    
    
    protected string $id_user;
    
    public function __construct(string $id_user = 'all') {
        $this->id_user = $id_user;
    }
    
    public static function instance(string $id_user = 'all'): static {
        return new static($id_user);
    }
    
    public static function list(string $id_user = 'all') {
        return static::instance($id_user)->get();
    }
    
    public static function default(string $id_user = 'all') {
        return static::instance($id_user)->get(true);
    }
    
    
    
    public function get(bool $is_default = false) {
        
        $signature = ModelUtils::createInstanceModel($this->model);
        $signatureType = ModelUtils::createInstanceModel($this->model_type);
        $signatureFile = ModelUtils::createInstanceModel($this->model_file);
        $fileSignature = ModelUtils::createInstanceModel($this->model_file_signature);
        $fileDisk = ModelUtils::createInstanceModel($this->model_file_disk);
        $fileDiskEntity = ModelUtils::createInstanceModel($this->model_file_disk_entity);
        $fileDiskToken = ModelUtils::createInstanceModel($this->model_file_disk_token);
        
        $query = $signature->query()
            ->where('is_default', '=', $is_default);
        
        
        if ($this->id_user !== 'all') {
            $query->where('id_user', '=', $this->id_user);
        }
        
        $listSignatureId = $query
            ->pluck($signature->getKeyName());
            // ->get($signature->getKeyName())
            // ->collect()
            // ->map(fn($m) => $m->getKey());
        
        $tableS = $signature->getTable();
        $tableSTy = $signatureType->getTable();
        $tableSF = $signatureFile->getTable();
        $tableFS = $fileSignature->getTable();
        
        $tableFD = $fileDisk->getTable();
        $tableFDE = $fileDiskEntity->getTable();
        $tableFDT = $fileDiskToken->getTable();
        
        $primS = $signature->getKeyName();
        $primSTy = $signatureType->getKeyName();
        $primSF = $signatureFile->getKeyName();
        $primFS = $fileSignature->getKeyName();
        
        $primFD = $fileDisk->getKeyName();
        $primFDE = $fileDiskEntity->getKeyName();
        $primFDT = $fileDiskToken->getKeyName();
        
        $queryType = $signatureType->query()
            ->join("{$tableSF}", "{$tableSF}.{$primSTy}", '=', "{$tableSTy}.{$primSTy}")
            ->join("{$tableFS}", "{$tableFS}.{$primFS}", '=', "{$tableSF}.{$primFS}")
            ->join("{$tableFD}", "{$tableFD}.{$primFD}", '=', "{$tableFS}.{$primFD}")
            ->join("{$tableFDE}", "{$tableFDE}.{$primFD}", '=', "{$tableFD}.{$primFD}")
            ->join("{$tableFDT}", "{$tableFDT}.{$primFDE}", '=', "{$tableFDE}.{$primFDE}")
            ->whereIn("{$tableSTy}.{$primS}", $listSignatureId)
            ->whereIn("{$tableSTy}.type", Type::get_map_value())
            // ->where("{$tableSF}.variant", '=', Variant::ORIGINAL->value)
            ->select([
                "{$tableSTy}.*",
                "{$tableSF}.variant",
                "{$tableFS}.file_client_name",
                "{$tableFDT}.token",
            ])
            ;
        
        $listSignatureType = $queryType
            ->get()
            ->collect()
            ->groupBy($primS)
            ->map(function($items) {
                return $items->groupBy('type')->map(function ($typeGroup) {
                    $result = [];
                    $byVariant = [];
                    
                    foreach ($typeGroup as $item) {
                        $variant = $item->variant;
                        $token = $item->token;
                        
                        if ($variant === Variant::ORIGINAL->value) {
                            $result['token_original'] = $token;
                        } elseif ($variant === Variant::THUMBNAIL->value) {
                            $result['token_thumbnail'] = $token;
                        }
                        
                        $byVariant[$variant] = $item;
                    }
                    
                    $baseItem = $byVariant[Variant::ORIGINAL->value] ?? reset($byVariant);
                    
                    $result = array_merge(
                        collect($baseItem->toArray())
                            ->filter(fn($v, $k) => $k !== 'token')
                            ->toArray(),
                        $result
                    );
                    
                    
                    return $result;
                });
            })
            // ->map(fn($m) => $m->keyBy('type'))
            // ->reduce(function ($carry, $item) use ($primS) {
            //     $signatureId = $item->{$primS};
            //     $type = $item->type;
                
            //     if (! isset($carry[$signatureId])) {
            //         $carry[$signatureId] = [];
            //     }
                
            //     $carry[$signatureId][$type] = $item->toArray();
            //     // if ($item->variant)
                
            //     return $carry;
            // }, [])
            ->toArray()
            ;
        
        return $listSignatureType;
    }
    
    
    
    
}