<?php

namespace App\Trait;

use Illuminate\Database\Eloquent\Model;
use App\Utils\ModelUtils;

/**
 * @mixin \App\Contracts\Enums\HasAssociatedModelEnum
 */
trait InteractWithModelEnum
{
    public static function instanceModel(): Model
    {
        return ModelUtils::createInstanceModel(static::model_class_name());
    }
    
    public static function getModelById(string $id): Model|null
    {
        $model = static::instanceModel();

        return $model->newQuery()
            ->where($model->getKeyName(), $id)
            ->first();
    }
}
