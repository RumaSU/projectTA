<?php

namespace App\Utils;

use App\Libraries\UuidHelper;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use Carbon\Carbon;

class RequestUtils {
    
    protected static ?array $CODES = null;
    private const PATH_JSON = __DIR__ . '/json/status_code.json';
    
    /**
     * Loads HTTP status codes from a JSON file.
     * Ensures lazy loading, only loading the file once.
     * @return void
     */
    protected static function load(): void
    {
        if (! is_null(static::$CODES)) {
            return;
        }
        if (! file_exists(static::PATH_JSON)) {
            Log::error("Status code reference file not found: " . static::PATH_JSON); // Add logging
            return;
        }
        
        $jsonContent = file_get_contents(static::PATH_JSON);
        if ($jsonContent === false) {
            Log::error("Failed to read status code reference file: " . static::PATH_JSON); // Add logging
            return;
        }
        
        $json = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("Failed to decode status code reference JSON: " . json_last_error_msg()); // Add logging
            return;
        }
        
        static::$CODES = $json;
    }
    
    /**
     * Finds status code details by its code.
     * @param string $code The status code to search for (e.g., "200", "4xx").
     * @return array|null An array of status code details, or null if not found.
     */
    public static function find(string $code): array|null {
        static::load();
        return static::$CODES[$code] ?? null;
    }
     
    /**
     * Returns all loaded status codes.
     * @return array An array containing all status codes loaded from the JSON, or an empty array if loading failed/not yet loaded.
     */
    public static function getCodes(): ?array {
        static::load();
        return static::$CODES ?? [];
    }
    
    /**
     * Resolves a given status code, converting generic formats (e.g., "2xx") to their numeric base (e.g., 200).
     * @param string $code The status code to resolve.
     * @return int The resolved numeric HTTP status code, or 400 if the code is unknown.
     */
    public static function resolveCodeStatus(string $code) {
        $find = static::find($code);
        if (! $find) {
            Log::error("Attempted to resolve unknown status code: {$code}");
            return 400;
        }
        
        if (! str_contains($find['code'], 'x')) {
            return (int) $find['code'];
        }
        
        return (int) (substr($find['code'], 0, 1) . '00');
    }
    
    
    /**
     * Creates and returns a Laravel JSON response.
     * Ensures the provided status code is valid or defaults to 400.
     * @param mixed $data The data to be encoded into the JSON response.
     * @param int $status The numeric HTTP status code for the response.
     * @param array $headers Additional HTTP headers for the response.
     * @param int $option JSON encoding options.
     * @return JsonResponse The JSON response object.
     */
    public static function createJsonResponse($data = [], int $status = 200, array $headers = [], int $option = 0): JsonResponse {
        
        $status = static::resolveCodeStatus((string) $status);
        
        if (! is_array($data)) {
            $data = (array) $data;
        }
        
        $time = Carbon::now()->toIso8601String();
        
        $defaultHeaders = [
            'Session-ID' => session()->getId(),
            'Response-ID' => UuidHelper::generate(),
            'Response-Time' => $time,
            'Response-Fingerprint' => UuidHelper::generate('v5', json_encode([$data, $time]))
        ];
        
        $headers = array_merge($headers, $defaultHeaders);
        
        return response()
            ->json($data, $status, $headers, $option);
    }
    
    /**
     * Checks if the given variable is an instance of Illuminate\Http\Request.
     * @param mixed $request The variable to check.
     * @return bool True if the variable is a Request instance, false otherwise.
     */
    public static function is_request($request): bool {
        return $request instanceof Request;
    }
    
}