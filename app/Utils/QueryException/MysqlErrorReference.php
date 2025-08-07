<?php

namespace App\Utils\QueryException;

use Illuminate\Support\Facades\Log;

class MysqlErrorReference
{
    protected static ?array $errors = null;
    protected static string $path = __DIR__ . '/json/mysql.json';
    
    protected static function load()
    {
        if (! is_null(self::$errors)) return;
        if (! file_exists(self::$path)) {
            Log::error("MySQL error reference file not found: " . self::$path); // Add logging
            return;
        }
        
        $jsonContent = file_get_contents(self::$path);
        if ($jsonContent === false) {
            Log::error("Failed to read MySQL error reference file: " . self::$path); // Add logging
            return;
        }
        
        $json = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("Failed to decode MySQL error reference JSON: " . json_last_error_msg()); // Add logging
            return;
        }
        
        if (!isset($json['errors']) || !is_array($json['errors'])) {
            Log::error("MySQL error reference JSON is malformed (missing 'errors' key or not array)."); // Add logging
            return;
        }
        
        $indexed = [];
        foreach($json['errors'] as $item) {
            $indexed[$item['code']] = $item;
        }
        
        self::$errors = $indexed;
    }
    
    public static function find($code): ?array
    {
        self::load();
        return self::$errors[$code] ?? null;
    }
}
