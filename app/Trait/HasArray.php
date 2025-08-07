<?php

namespace App\Trait;

use Illuminate\Support\Arr;

trait HasArray {
    
    public static function array_is_list(array $array) {
        $i = 0;
        foreach ($array as $key => $_) {
            if ($key !== $i) {
                return false;
            }
            $i++;
        }
        
        return true;
    }
    
    public static function array_flatten($array) {
        if (! is_array($array)) return null;
        
        $result = [];
        foreach($array as $item) {
            if (is_array($item)) {
                $result = array_merge($result, static::array_flatten($item));
            } else {
                $result[] = $item;
            }
        }
        return $result;
    }
    
}