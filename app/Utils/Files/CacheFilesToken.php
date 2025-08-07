<?php

namespace App\Utils;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

class CacheFilesToken {
    protected static string $basePath = 'cache_tokens';

    public static function getTodayPath(): string
    {
        return static::$basePath . '/' . now()->toDateString();
    }

    public static function getFilePath(string $token): string
    {
        return static::getTodayPath() . '/' . $token . '.json';
    }

    public static function put(string $token, array $data): bool
    {
        $encrypted = Crypt::encrypt($data);
        $hash = hash('sha256', $encrypted);

        $payload = json_encode([
            'data' => $encrypted,
            'hash' => $hash,
        ], JSON_UNESCAPED_SLASHES);

        return Storage::put(static::getFilePath($token), $payload, 'private');
    }

    public static function get(string $token): ?array
    {
        $path = static::getFilePath($token);

        if (!Storage::exists($path)) {
            return null;
        }

        $content = json_decode(Storage::get($path), true);
        if (!is_array($content) || !isset($content['data'], $content['hash'])) {
            return null;
        }

        // Validasi integritas hash
        if (hash('sha256', $content['data']) !== $content['hash']) {
            return null; // rusak atau diubah
        }

        try {
            return Crypt::decrypt($content['data']);
        } catch (\Exception $e) {
            return null; // gagal decrypt
        }
    }

    public static function delete(string $token): bool
    {
        return Storage::delete(static::getFilePath($token));
    }

    public static function cleanOld(int $days = 7): void
    {
        $cutoff = now()->subDays($days);
        $dirs = Storage::directories(static::$basePath);

        foreach ($dirs as $dir) {
            $date = basename($dir);
            if (strtotime($date) !== false && $date < $cutoff->toDateString()) {
                Storage::deleteDirectory($dir);
            }
        }
    }
}
