<?php

namespace App\Trait\Enum;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use App\Utils\ModelUtils;

trait ProcessDocuments {
    
    protected static string $JOB_MODEL_CLASS = \App\Models\Jobs\Documents\Process::class;
    
    public static function get_cases(bool $to_array = false): array|Collection {
        $collection = collect(static::cases());
        
        return $to_array
            ? $collection->toArray()
            : $collection;
    }
    
    public static function instanceModel(): Model {
        return ModelUtils::createInstanceModel(static::$JOB_MODEL_CLASS);
    }
    
    public static function getModelById(string $id): Builder|null {
        $model = static::instanceModel();
        
        $query = $model->query()
            ->where($model->getKeyName(), '=', $id);
            
        return $query->exists() ? $query->first() : null;
    }
    
}