<?php

namespace App\Utils;

use App\Utils\LogUtils;

use App\Trait\InteractWithFilesystemConfig;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Throwable;

// Not support cloud storage
class FileUtils {
    use InteractWithFilesystemConfig;
    
    protected int $limit_size;
    protected File $file;
    
    
    private function __construct() {}
    
    public static function getFileContent(string $pathname, ?int $limit = null) {
        
        if (! static::is_valid($pathname)) {
            return null;
        }
        
        $limit = $limit ?? static::$DEFAULT_MAX_FILE_SIZE;
        
        try {
            $file = new File($pathname);
            return $file->getSize() <= $limit ? $file->getContent() : null;
        } catch (FileException $e) {
            LogUtils::logException($e);
            return null;
        }
    }
    
    public static function exists(string $pathname): bool {
        return file_exists($pathname);
    }
    
    public static function delete(string $pathname, bool $force = false) {
        if (is_dir($pathname) && !$force) {
            return false;
        }
        
        try {
            $deleted = unlink($pathname);   
        } catch (Throwable $e) {
            LogUtils::logException($e);
            return false;
        }

        return true;
    }
    
    public static function is_valid($pathname) {
        return is_readable($pathname) && is_file($pathname);
    }
    
    public static function get_default_max_size() {
        return static::$DEFAULT_MAX_FILE_SIZE;
    }
    
    public static function normalize(string $path, string $separator = DIRECTORY_SEPARATOR): ?string {
        $normalized = preg_replace('#[\\/\\\\]+#', $separator, $path);
        return preg_replace("#{$separator}+$#", '', $normalized);
    }
    
    
    public static function create_file_instance(string $pathname): File|null {
        if (! static::is_valid($pathname)) {
            return null;
        }
        
        try {
            return new File($pathname);
        } catch (FileException $e) {
            LogUtils::logException($e);
            return null;
        }
    }
    
    public static function copy(string $source, string $target, $delete = false): bool {
        
        if (! static::is_valid($source)) {
            return false;
        }
        
        $file = static::create_file_instance($source);
        if (!$file || $file->getSize() > static::$DEFAULT_MAX_FILE_SIZE) {
            return false;
        }
        
        $dir = dirname($target);
        if (! static::exists($target)) {
            mkdir($dir, 0755, true);
        }
        
        $copied = copy($source, $target);
        if (! $copied) {
            return false;
        }
        
        if ($copied && $delete) {
            static::delete($source);
        }
        
        return true;
    }
    
    public static function copyStream(string $source, string $targetDir, $delete = false, ?string $targetFilename = null): bool {
        if (! static::is_valid($source)) {
            return false;
        }
        
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        $file = static::create_file_instance($source);
        $filename = $targetFilename ?? $file->getFilename();
        $target = static::normalize($targetDir) . DIRECTORY_SEPARATOR . $filename;
        
        $src = fopen($source, 'rb');
        $dst = fopen($target, 'wb');
        
        if (!$src || !$dst) {
            if ($src) fclose($src);
            if ($dst) fclose($dst);
            
            return false;
        }
        
        $success = stream_copy_to_stream($src, $dst) !== false;
        
        fclose($src);
        fclose($dst);
        
        if ($success && $delete) {
            static::delete($source);
        }
        
        return $success;
    }
    
    public static function move(string $from, string $to): bool {
        $targetDir = dirname($to);
        $targetFilename = basename($to);
        return static::copyStream($from, $targetDir, true, $targetFilename);
    }
    
    
    
    public static function handle(string $pathname, int|null $limit = null) {
        
        if (! static::is_valid($pathname)) {
            return null;
        }
        
        try {
            $instance = new static();
            $instance->file = new File($pathname);
            $instance->limit_size = $limit ?? static::get_default_max_size();
            
            return $instance;
            
        } catch (FileException $e) {
            LogUtils::logException($e);
            return null;
        }
    }
    
    public function file() {
        return $this->file;
    }
    
    public function getContent() {
        return $this->getSize() <= $this->limit_size
            ? $this->file->getContent()
            : null;
    }
    
    public function getSize() {
        return $this->file->getSize();
    }
    
    public function getFilename() {
        return $this->file->getFilename();
    }
    
    public function getMimeType() {
        return $this->file->getMimeType();
    }
    
    public function getExtension() {
        return $this->file->guessExtension();
    }
    
    public function getType() {
        return $this->file->getType();
    }
    
    public function getPath() {
        return $this->file->getPath();
    }
    
    public function getPathname() {
        return $this->file->getPathname();
    }
    
    public function getModifiedTime() {
        return $this->file->getMTime();
    }
    
    public function getBasename() {
        return $this->file->getBasename();
    }
    
    public function getMetadata() {
        return [
            'filename' => $this->file->getFilename(),
            'size' => $this->file->getSize(),
            'mime' => $this->file->getMimeType(),
            'extension' => $this->file->getExtension(),
            'type' => $this->file->getType(),
            'pathname' => $this->file->getPathname(),
            'basename' => $this->file->getBasename(),
        ];
    }
    
    public function is_image(): bool {
        return str_starts_with($this->getMimeType(), 'image/');
    }
}