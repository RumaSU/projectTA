<?php

namespace App\Utils;

use App\Utils\StorageUtils;

class ChunkUtils {
    protected static array $CONFIG = [];
    
    
    protected const MAP_CHUNK_REQUEST = [
        'resumable' => [
            "resumableChunkNumber",
            "resumableChunkSize",
            "resumableCurrentChunkSize",
            "resumableTotalSize",
            "resumableType",
            "resumableIdentifier",
            "resumableFilename",
            "resumableRelativePath",
            "resumableTotalChunks",
        ]
    ];
    
    
    protected static function loadConfig() {
        if (! empty(static::$CONFIG)) return;
        static::$CONFIG = config('chunk-upload') ?? null;
    }
    
    public static function getConfig(string $key = '') {
        static::loadConfig();
        if ($key === '') {
            return static::$CONFIG;
        }
        
        return static::$CONFIG[$key] ?? null;
    }
    
    public static function getConfigStorage() {
        static::loadConfig();
        return static::getConfig('storage');
    }
    
    public static function getConfigStorageDisk(string $default = 'local') {
        return static::getConfigStorage()['disk'] ?? $default;
    }
    public static function getConfigStorageChunks() {
        return static::getConfigStorage()['chunks'] ?? null;
    }
    
    public static function getMapChunk(string $key) {
        return static::MAP_CHUNK_REQUEST[$key] ?? null;
    }
    
    public static function getChunkRequest(string $key, \Illuminate\Http\Request $request) {
        $mapChunk = static::getMapChunk($key);
        if (! $mapChunk) return null;
        
        $col = collect($request)
            ->filter(fn($v, $k) => in_array($k, $mapChunk))
            ->toArray();
        
        return count($col) === count($mapChunk) 
            ? $col
            : null;
    }
    
    public static function getChunkMetadataFile($filename) {
        $chunkDir = static::getConfigStorageChunks();
        if ($chunkDir === null) {
            return null;
        }

        $chunkDir = rtrim($chunkDir, '/\\'); 
        
        if (!str_starts_with($filename, $chunkDir . '/')) {
            $filename = $chunkDir . '/' . ltrim($filename, '/\\'); // Hapus slash di awal filename jika ada
        }
        
        
        return StorageUtils::get_metadata(static::getConfigStorageDisk(), $filename);
    }
    
    
    public static function checkMapChunk($key) {
        return array_key_exists($key, static::MAP_CHUNK_REQUEST);
    }
    
    public static function deleteChunk($filename) {
        $chunkDir = static::getConfigStorageChunks();
        if ($chunkDir === null) {
            return false;
        }
        
        $chunkDir = rtrim($chunkDir, '/\\');
        
        if (!str_starts_with($filename, $chunkDir . '/')) {
            $filename = $chunkDir . '/' . ltrim($filename, '/\\');
        }
        
        return StorageUtils::delete(static::getConfigStorageDisk(), $filename );
    }
    
}