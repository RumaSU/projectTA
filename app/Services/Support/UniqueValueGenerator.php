<?php

namespace App\Services\Support;

use Illuminate\Support\Str;

use App\Libraries\UuidHelper;
use App\Libraries\StringHelper;

use App\Utils\DatabaseUtils;
use RuntimeException;

class UniqueValueGenerator {
    
    private const MAX_GENERATE_CHECK_UUID = 5;
    private const MAX_GENERATE_CHECK_STRING = 3;
    private const MAX_STRING_OCCURS = 5;
    
    
    
    /**
     * Summary of uuid
     * @param string $version
     * @param string $column
     * @param string $table
     * @param string|null $connection
     * @param string|null $hash
     * @return string
     */
    public static function uuid(string $version = 'v4', string $column, string $table, string|null $connection = null, string|null $hash = null): string {
        $currentVersion = $version;
        
        if (StringHelper::contains($currentVersion, ['v4', 'v5'], false) && is_null($hash)) {
            $hash = Str::random(32);
        }
        
        $uuid = UuidHelper::generate($currentVersion, $hash)->toString();
        
        $count = 1;
        while(DatabaseUtils::connection($connection)->exists($uuid, $table, $column)) {
            
            if ($count >= static::MAX_GENERATE_CHECK_UUID) {
                $currentVersion = 'v5';
                $hash = Str::random(32);
            }
            
            $uuid = UuidHelper::generate($currentVersion, $hash)->toString();
            $count += 1;
            
            if ($count > static::MAX_GENERATE_CHECK_UUID) break;
        } 
        
        return $uuid;
    }
     
    
    /**
     * Summary of stringHelper
     * @param int $length
     * @param string $column
     * @param string $table
     * @param string|null $connection
     * @param bool $upper
     * @param bool $lower
     * @param bool $number
     * @param bool $symbol
     * @return string
     */
    public static function stringByHelper(int $length = 16, string $column, string $table, string|null $connection = null, bool $upper = true, bool $lower = true, bool $number = true) {
        $string = StringHelper::random($length, $upper, $lower, $number, false);
        
        $count = 1;
        $occurs = 1;
        
        while($column && $table && DatabaseUtils::connection($connection)->exists($string, $table, $column)) {
            
            if ($count > static::MAX_GENERATE_CHECK_STRING && $occurs < static::MAX_STRING_OCCURS) {
                $length += 1;
                
                $count = 0;
                $occurs += 1;
            }
            
            $string = StringHelper::random($length, $upper, $lower, $number, false);
            $count += 1;
            
            if ($occurs > static::MAX_GENERATE_CHECK_STRING) {
                throw new RuntimeException("Failed to generate unique string. Tried {$occurs} length increases and {$count} attempts per length.");
            }
            
        }
        
        return $string;
    }
    
    
    /**
     * Summary of stringIlluminate
     * @param int $length
     * @param string $column
     * @param string $table
     * @param string|null $connection
     * @return string
     */
    public static function stringByIlluminate(int $length = 16, string $column, string $table, string|null $connection = null) {
        $string = Str::random($length);
        
        $count = 1;
        $occurs = 1;
        
        while($column && $table && DatabaseUtils::connection($connection)->exists($string, $table, $column)) {
            
            if ($count > static::MAX_GENERATE_CHECK_STRING && $occurs < static::MAX_STRING_OCCURS) {
                $length += 1;
                
                $count = 0;
                $occurs += 1;
            }
            
            $string = Str::random($length);
            $count += 1;
            
            if ($occurs > static::MAX_GENERATE_CHECK_STRING) {
                throw new RuntimeException("Failed to generate unique string. Tried {$occurs} length increases and {$count} attempts per length.");
            }
            
        }
        
        return $string;
    }
    
    
    public static function stringByHelperContains(int $length = 16, string $column, string $table, string|null $connection = null, bool $upper = true, bool $lower = true, bool $number = true) {
        $string = StringHelper::random($length, $upper, $lower, $number, false);
        
        $count = 1;
        $occurs = 1;
        
        $tableInstance = DatabaseUtils::connection($connection)
            ->database->table($table);
        
        while($column && $table && $tableInstance->where($column, 'like', "%{$string}%")->exists()) {
            
            if ($count > static::MAX_GENERATE_CHECK_STRING && $occurs < static::MAX_STRING_OCCURS) {
                $length += 1;
                
                $count = 0;
                $occurs += 1;
            }
            
            $string = StringHelper::random($length, $upper, $lower, $number, false);
            $count += 1;
            
            if ($occurs > static::MAX_GENERATE_CHECK_STRING) {
                throw new RuntimeException("Failed to generate unique string. Tried {$occurs} length increases and {$count} attempts per length.");
            }
            
        }
        
        return $string;
    }
    
    public static function stringByIlluminateContains(int $length = 16, string $column, string $table, string|null $connection = null) {
        $string = Str::random($length);
        
        $count = 1;
        $occurs = 1;
        
        $tableInstance = DatabaseUtils::connection($connection)
            ->database->table($table);
        
        while($column && $table && $tableInstance->where($column, 'like', "%{$string}%")->exists()) {
            
            if ($count > static::MAX_GENERATE_CHECK_STRING && $occurs < static::MAX_STRING_OCCURS) {
                $length += 1;
                
                $count = 0;
                $occurs += 1;
            }
            
            $string = Str::random($length);
            $count += 1;
            
            if ($occurs > static::MAX_GENERATE_CHECK_STRING) {
                throw new RuntimeException("Failed to generate unique string. Tried {$occurs} length increases and {$count} attempts per length.");
            }
            
        }
        
        return $string;
    }
    
}