<?php

namespace App\Libraries;

class Base64Helper {
    
    public const MIN_BYTE_BASE64_DECODE = 100;
    
    
    /**
     * Encodes various data types into a Base64 string.
     * 
     * @param mixed $data The data to encode.
     * @return string The Base64 encoded string.
     */
    public static function encode($data) {
        if (!is_string($data)) {
            $payload = [
                'value' => $data,
                'payload_encoding_type' => gettype($data),
            ];
            $data = json_encode($payload);
        } elseif (json_validate($data)) {
            $payload = [
                'value' => $data,
                'payload_encoding_type' => 'string-json',
            ];
            $data = json_encode($payload);
        }
        
        return base64_encode($data);
    }
    
    /**
     * Decodes a Base64 string and attempts to restore its original data type.
     *
     * @param string $base64String
     * @return mixed|null
     */
    public static function decode($base64String) {
        if (! is_string($base64String)) {
            return null;
        }
        
        $result = base64_decode($base64String);
        
        // if (! json_validate($result)) {
        //     return $result;
        // }
        
        $json = json_decode($result);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $result;
        }
         
        if (! property_exists($json, 'payload_encoding_type')) {
            return $json;
        }
        
        $type = $json->payload_encoding_type ?? null;
        
        if ($type === 'array') {
            $result = json_decode(json_encode($json->value), true);
        } else {
            $result = $json->value;
        }
        
        return $result;
    }
    
    /**
     * Validates if a base64 string is a decodable image.
     *
     * @param string $string
     * @param int|null $min_byte
     * @return array|null
     */
    public static function validate_imageable(string $string, int|null $min_byte = null): array|null {
        $base64data = preg_match('/^data:image\/(\w+);base64,/', $string, $matches)
            ? substr($string, strpos($string, ',') + 1)
            : $string;
        
        $decoded = base64_decode($base64data, true);
        if ($decoded === false) {
            return null;
        }
        
        $min_byte = ($min_byte !== null && $min_byte < 1)
            ? static::MIN_BYTE_BASE64_DECODE
            : $min_byte;
        
        if (! is_null($min_byte) && strlen($decoded) < $min_byte) {
            return null;
        }
        
        $image_info = @getimagesizefromstring($decoded);
        
        if (! (bool) $image_info || !isset($image_info['mime']) || !str_starts_with($image_info['mime'], 'image/')) {
            return null;
        }
        
        return [
            'base64_string' => $base64data,
            'decoded_content' => $decoded,
            'image_info' => $image_info
        ];
    }
    
    /**
     * Checks if a string is an imageable base64.
     *
     * @param string $string
     * @param int|null $min_byte
     * @return bool
     */
    public static function is_imageable(string $string, int|null $min_byte = null): bool {
        return static::validate_imageable($string, $min_byte) !== null;
    }
    
    /**
     * Extracts image information and raw content from a base64 image string.
     *
     * @param string $base64string
     * @param bool $as_object
     * @return array|object|null
     */
    public static function extract_image(string $base64string, $as_object = false): array|object|null {
        $validation_result = self::validate_imageable($base64string);

        if ($validation_result === null) {
            return null;
        }

        $decoded = $validation_result['decoded_content'];
        $image_info = $validation_result['image_info'];

        $result_data = [
            'base64_string' => $validation_result['base64_string'],
            'width' => $image_info[0],
            'height' => $image_info[1],
            'total_pixels' => $image_info[0] * $image_info[1],
            'byte_size' => strlen($decoded),
            'mime' => $image_info['mime'],
            'content' => $decoded
        ];

        // Implementation for $as_object
        if ($as_object) {
            return (object) $result_data;
        }

        return $result_data;
        
    }
    
    public static function get_base64_mime(string $base64string): string|null {
        return static::extract_image($base64string)['mime'] ?? null;
    }
    
}