<?php

namespace App\Libraries;

use Illuminate\Support\Str;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

class UuidHelper {
    private const MAP_VERSION = [
        'v1', 'v2', 'v3', 'v4', 'v5', 'v6', 'v7'
    ];
    
    /**
     * Resolves the UUID version, defaulting to 'v4' if the provided version is invalid.
     *
     * @param string $version The UUID version to resolve (e.g., 'v1', 'v4', 'v7').
     * @return string The validated or defaulted UUID version.
     */
    public static function resolveUuidVersion($version) {
        $version = strtolower($version);
        
        return static::checkVersion($version)
            ? $version
            : 'v4';
    }
    
    /**
     * Checks if a given UUID version is supported by the helper.
     *
     * @param string $version The UUID version to check.
     * @return bool True if the version is supported, false otherwise.
     */
    public static function checkVersion($version) {
        return in_array($version, static::MAP_VERSION);
    }
    
    /**
     * Validates if a string is a well-formed UUID.
     *
     * @param string $uuid The string to validate.
     * @return bool True if the string is a valid UUID, false otherwise.
     */
    public static function isValid($uuid) {
        return Uuid::isValid($uuid);
    }
    
    /**
     * Generates a new UUID of a specified version.
     *
     * @param string $version The desired UUID version (defaults to 'v4').
     * @param string|null $hash Optional hash for name-based UUIDs (v3, v5).
     * @return UuidInterface A new UUID object.
     */
    public static function generate(string $version = 'v4', $hash = null): UuidInterface
    {
        $version = static::resolveUuidVersion($version);
        if (! $hash) $hash = Str::random();
        switch($version) {
            case 'v1': return Uuid::uuid1();
            case 'v2': return Uuid::uuid2(Uuid::DCE_DOMAIN_PERSON);
            case 'v3': return Uuid::uuid3(Uuid::NAMESPACE_URL, $hash); // md5
            case 'v4': return Uuid::uuid4();
            case 'v5': return Uuid::uuid5(Uuid::NAMESPACE_URL, $hash); // sha-1
            case 'v6': return Uuid::uuid6();
            case 'v7': return Uuid::uuid7();
            default:   return Uuid::uuid4();
        }
    }
}