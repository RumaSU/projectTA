<?php

namespace App\Libraries;

class ArrayHelper {
    
    private const MAP_DATA_TYPES = [
        'int', 'integer', 'string', 'bool', 'boolean', 'float', 'double', 'array', 'object', 'null', 'callable',
    ];
    
    /**
     * Determines if an array is a list (numerically indexed from 0 to count-1).
     * @param mixed $array The variable to check.
     * @return bool True if the variable is a list, false otherwise.
     */
    public static function is_list($array): bool {
        if (! is_array($array)) {
            return false;
        }
        
        if (version_compare(PHP_VERSION, '8.1.0', '>=')) {
            return array_is_list($array);
        }
        
        if ($array === []) {
            return true;
        }
        
        return array_keys($array) === range(0, count($array) - 1);
    }
    
    /**
     * Checks if one or more keys exist in an array.
     *
     * @param string|array $keys The key(s) to check for. Can be a single string or an array of strings.
     * @param array $array The array to check within.
     * @return bool True if all specified keys exist in the array, false otherwise.
     */
    public static function key_exists(array|string $keys, array $array): bool {
        
        if (is_string($keys)) {
            return array_key_exists($keys, $array);
        }
        
        foreach ($keys as $key) {
            if (! array_key_exists($key, $array)) {
                return false;
            }
        }
         
        return true;
    }
    
    /**
     * Recursively checks if one or more keys exist in a nested array.
     *
     * @param string|array $keys The key(s) to search for. Can be a single string or an array of strings.
     * @param array $array The array to search within (may contain nested arrays).
     * @return bool True if all specified keys are found anywhere in the array (including nested), false otherwise.
     */
    public static function key_exists_recursive(string|array $keys, array $array): bool {
        
        $keys = is_string($keys) ? [$keys] : $keys;
        $foundKeys = [];
        
        $iterator = function(array $arr) use (&$iterator, $keys, &$foundKeys): bool {
            foreach ($arr as $key => $value) {
                
                if (in_array($key, $keys) && !in_array($key, $foundKeys)) {
                    $foundKeys[] = $key;
                    
                    if (count($foundKeys) === count($keys)) {
                        return true;
                    }
                }
                
                if (is_array($value)) {
                    if ($iterator($value)) {
                        return true;
                    }
                }
            }
            
            return false;
        };
        
        return $iterator($array);
    }
    
    /**
     * Check if a value in the array matches the expected type.
     *
     * @param string $key The key to check.
     * @param string $type Expected type (e.g., string, int, array, etc).
     * @param array $array The array containing the value.
     * @return bool
     */
    public static function check_value_type(string $key, string $type, array $array): bool {
        if (! static::key_exists($key, $array)) return false;
        
        $value = $array[$key];
        
        return match ($type) {
            'int', 'integer'   => is_int($value),
            'string'           => is_string($value),
            'bool', 'boolean'  => is_bool($value),
            'float', 'double'  => is_float($value),
            'array'            => is_array($value),
            'object'           => is_object($value),
            'null'             => is_null($value),
            'callable'         => is_callable($value),
            default            => false,
        };
    } 
    
    /**
     * Check if multiple keys in the array match their expected types.
     *
     * @param array<string, string> $types Array of key => expected type.
     * @param array $array The data array to validate.
     * @return bool
     */
    public static function check_multi_value_type(array $types = ['key' => 'string'], array $array): bool {
        foreach ($types as $key => $expectedType) {
            
            if (! static::key_exists($key, $array)) {
                return false;
            }
            
            $value = $array[$key];
            
            $isValid = match (strtolower($expectedType)) {
                'int', 'integer'   => is_int($value),
                'string'           => is_string($value),
                'bool', 'boolean'  => is_bool($value),
                'float', 'double'  => is_float($value),
                'array'            => is_array($value),
                'object'           => is_object($value),
                'null'             => is_null($value),
                'callable'         => is_callable($value),
                default            => false,
            };
            
            if (! $isValid) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Flattens a multi-dimensional array into a single-dimensional array.
     * 
     * @param array $array The array to flatten.
     * @return array The flattened array.
     */
    public static function flatten(array $array, string $prefix = '') {
        $result = [];
        
        foreach($array as $key => $value) {
            $fullkey = $prefix === '' ? $key : $prefix . '.' . $key;
            
            if (is_array($value)) {
                $result += static::flatten($array, $fullkey);
            } else {
                $result[$fullkey] = $value;
            }
        }
        
        return $result;
    }
    
    /**
     * Merge string or array value into another array.
     * @param array|string $values
     * @param array $array
     * @return array
     */
    public static function merge(array|string $values, array $array): array {
        $values = is_string($values) ? [$values] : $values;
        return array_merge($array, $values);
    }
    
    /**
     * Recursively filters values in an array.
     *
     * @param array<string, mixed> $array The array to filter.
     * @param array|string $types Optional types to keep (defaults to 'null' mode if empty).
     * @param bool $allow_empty If true, includes empty string '' and empty array [], but still excludes null.
     * @return array<string, mixed>
     */
    public static function filter_recursive(array $array, array|string $types = 'null', bool $allow_empty = false): array {
        $types = is_string($types) ? [$types] : $types;
        
        $validTypes = array_intersect(
            array_map('strtolower', $types),
            static::MAP_DATA_TYPES
        );
        
        $isNullMode = empty($validTypes) || in_array('null', $validTypes, true);
        
        $result = [];
        
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $filtered = static::filter_recursive($value, $types);
                
                if (! empty($filtered) || ( $allow_empty && is_array($filtered) )) {
                    $result[$key] = $filtered;
                }
                
            } else {
                if ($isNullMode) {
                    
                    if (is_null($value)) {
                        continue;
                    }
                    
                    if (!$allow_empty && $value === '') {
                        continue;
                    }
                    
                    $result[$key] = $value;
                    
                    // if (!is_null($value) && $value !== '' && !$allow_empty_string) {
                    //     $result[$key] = $value;
                    // } elseif ($allow_empty_string) {
                    //     $result[$key] = $value;
                    // }
                } else {
                    if (in_array(gettype($value), $validTypes, true)) {
                        $result[$key] = $value;
                    }
                }
            }
        }
        
        return $result;
        
    }
    
    
    
    public static function ksort_recursive(array &$array) {
        ksort($array);
        
        foreach($array as &$value) {
            if (is_array($value)) {
                static::ksort_recursive($value);
            }
        }
    }
}