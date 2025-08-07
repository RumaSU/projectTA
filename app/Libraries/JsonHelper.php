<?php

namespace App\Libraries;

use JsonException;
use Symfony\Component\HttpFoundation\File\File;

use InvalidArgumentException;
use RuntimeException;

class JsonHelper {
    // 8.3.0
    private const ACCEPT_MIME = [
        'application/json',
        'text/json', 
        'text/x-json',
    ];
    
    private const MAX_FILE = 4 * 1024 * 1024;
    
    /**
     * Decodes JSON content from a given file.
     *
     * @param string|File $file The path to the JSON file or a Symfony File object.
     * @param bool|null $associative When TRUE, returned objects will be converted into associative arrays. Default is FALSE.
     * @param int $depth User specified recursion depth. Default is 512.
     * @param int $flags Bitmask of JSON_OPTIONS constants. Default is 0.
     * @return mixed The decoded JSON data (array if $associative is true, object otherwise).
     * @throws InvalidArgumentException If the file does not exist, has an invalid MIME type, or exceeds the maximum allowed size.
     * @throws RuntimeException If there is an error during JSON decoding.
     */
    public static function decode_file(string|File $file, bool|null $associative = null, int $depth = 512, int $flags = 0) {
        $test = fopen('', '');
        if (is_string($file)) {
            
            if (! file_exists($file)) {
                throw new InvalidArgumentException("File not found at path: " . $file);
            }
            
            $file = new File($file);
            
        }
        
        if (! in_array($file->getMimeType(), static::ACCEPT_MIME)) {
            throw new InvalidArgumentException("Invalid file MIME type. Only JSON files are accepted.");
        }
        
        if ($file->getSize() > static::MAX_FILE) {
            throw new InvalidArgumentException("File size exceeds the maximum allowed limit of " . static::MAX_FILE . " bytes.");
        }
        
        $json = json_decode($file->getContent(), $associative, $depth, $flags);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("JSON decoding error: " . json_last_error_msg());
        }
        
        return $json;
    }
    
    
    public static function validate($values, int $depth = 512, int $flags = 0) {
        if (!is_string($values)) {
            return false;
        }
        
        if (PHP_VERSION_ID >= 80300) {
            return json_validate($values, $depth, $flags);
        }
        
        try {
            json_decode($values, false, $depth, $flags | JSON_THROW_ON_ERROR);
            return true;
        } catch (\JsonException $e) {
            return false;
        }
        
        // return json_validate($values, $depth, $flags);
    }
    
    public static function encode($values, int $depth = 512, int $flags = 0) {
        return json_encode($values, $depth, $flags);
    }
    
    public static function decode($values, bool|null $as_array = null, int $depth = 512, int $flags = 0) {
        return json_decode($values, $as_array, $depth, $flags);
    }
    
    
    
}