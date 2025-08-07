<?php

namespace App\Library;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Symfony\Component\Mime\MimeTypes;

use App\Library\Helper as LibHelper;
use App\Library\User as LibUser;

class FileHelper {
    
    protected static array $map = [
        // Document
        'application/pdf' => 'pdf',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        'application/vnd.ms-powerpoint' => 'ppt',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',

        // Images
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
        'image/svg+xml' => 'svg',
        'image/bmp' => 'bmp',
        'image/tiff' => 'tif',

        // Audio
        'audio/mpeg' => 'mp3',
        'audio/wav' => 'wav',
        'audio/ogg' => 'ogg',
        'audio/x-aac' => 'aac',

        // Video
        'video/mp4' => 'mp4',
        'video/x-msvideo' => 'avi',
        'video/x-matroska' => 'mkv',
        'video/quicktime' => 'mov',

        // Archives
        'application/zip' => 'zip',
        'application/x-rar-compressed' => 'rar',
        'application/x-7z-compressed' => '7z',
        'application/gzip' => 'gz',

        // Text
        'text/plain' => 'txt',
        'text/html' => 'html',
        'text/css' => 'css',
        'application/javascript' => 'js',
        'application/json' => 'json',
    ];

    /**
     * Return the appropriate extension for a given MIME type.
     *
     * @param string|null $mime
     * @param string $default
     * @return string
     */
    public static function getExtension(?string $mime, string $default = 'bin'): string
    {
        return self::$map[$mime] ?? $default;
    }
    
    /**
     * Copy file between storage disks using stream.
     *
     * @param array $source ['disk' => '...', 'path' => '...']
     * @param array $target ['disk' => '...', 'path' => '...']
     * @param bool $delete  If true, delete source after copy (move behavior)
     * 
     * @return bool
     */
    public static function copyFile($source, $target, $delete = false) {
        
        if (! Storage::disk($source['disk'])->exists($source['path'])) {
            return false;
        }
        
        $stream = Storage::disk($source['disk'])->readStream($source['path']);
        if (! $stream) {
            return false;
        }
        
        $success = Storage::disk($target['disk'])->put($target['path'], $stream);
        
        if (is_resource($stream)) {
            fclose($stream);
        }
        
        if (! $success) {
            return false;
        }
        
        if ($delete) {
            Storage::disk($source['disk'])->delete($source['path']);
        }
        
        return true;
    }
    
    /**
     * Summary of getMimeType
     * @param string $disk
     * @param string $path
     * @return string|bool
     */
    public static function getMimeType($disk, $path) {
        return MimeTypes::getDefault()->guessMimeType(Storage::disk($disk)->path($path));
    }
    
    
    public static function checkExtension(string $extension) {
        return in_array($extension, self::$map);
    }
    
    
    
}