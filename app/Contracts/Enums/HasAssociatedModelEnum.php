<?php

namespace App\Contracts\Enums;

use Illuminate\Database\Eloquent\Model;

interface HasAssociatedModelEnum
{
    /** @return class-string<Model>|array{class: class-string<Model>, additional?: mixed} */
    public static function model_class_name(): string|array;

    public static function instanceModel(): Model;
    public static function getModelById(string $id): Model|null;
}
