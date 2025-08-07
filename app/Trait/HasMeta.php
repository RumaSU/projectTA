<?php

namespace App\Trait;

use App\Libraries\ArrayHelper;

trait HasMeta {
    // Restricted check
    protected static function checkMeta(array $meta, array $requiredKeys, bool $strict = true): bool {
    $keys = static::extractKeys($meta);
        
        if ($strict) {
            return count(array_intersect($keys, $requiredKeys)) === count($requiredKeys);
        }
        
        return ! empty(array_intersect($keys, $requiredKeys));
    }
    
    protected static function extractKeys(array $data) {
        return ArrayHelper::is_list($data)
            ? $data
            : array_keys($data);
    }
}