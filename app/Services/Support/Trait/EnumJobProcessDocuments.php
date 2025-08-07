<?php

namespace App\Services\Support\Trait;

use App\Utils\ModelUtils;
use Illuminate\Support\Collection;

trait EnumJobProcessDocuments {
    public const JOB_MODEL_CLASS = \App\Models\Jobs\Documents\Process::class;
    
    public static function get_cases(bool $to_array = false): array|Collection {
        $collection = collect(static::cases());
        
        return $to_array
            ? $collection->toArray()
            : $collection;
    }
    
    public static function instanceModel(): \Illuminate\Database\Eloquent\Model {
        return ModelUtils::createInstanceModel(static::JOB_MODEL_CLASS);
    }
    
    public static function getModelById(string $id): \Illuminate\Database\Eloquent\Builder|null {
        $model = static::instanceModel();
        
        $query = $model->query()
            ->where($model->getKeyName(), '=', $id);
            
        return $query->exists() ? $query->first() : null;
    }
    
}