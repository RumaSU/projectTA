<?php

namespace App\Utils;

use App\Utils\FileUtils;
use App\Trait\InteractWithFilesystemConfig;

use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

// Not support cloud storage
class StorageUtils {
    use InteractWithFilesystemConfig;
    
    
    public static function resolvePath(string $disk, string $path, bool $mustExist = true): ?string {
        if (!static::checkDisk($disk)) {
            return null;
        }
        
        $fullpath = Storage::disk($disk)->path($path);
        $full = FileUtils::normalize($fullpath);
        return $mustExist && !Storage::disk($disk)->exists($path) 
            ? null 
            : $full;
    }
    
    public static function is_file(string $disk, string $path): bool {
        $pathname = static::resolvePath($disk, $path);
        return $pathname !== null 
            && is_file($pathname);
    }
    
    public static function is_dir(string $disk, string $path): bool {
        $pathname = static::resolvePath($disk, $path);
        return $pathname !== null 
            && is_dir($pathname);
    }
    
    
    public static function exists(string $disk, string|null $path = null): bool {
        if (! static::checkDisk($disk)) return false;
        return Storage::disk($disk)->exists($path);
    }
    
    public static function delete(string $disk, string $path): bool {
        if (! static::checkDisk($disk)) return false;
        
        return Storage::disk($disk)->delete($path);
    }
    
    public static function get_metadata(string $disk, string $path): ?array {
        $pathname = static::resolvePath($disk, $path);
        if (! $pathname) {
            return null;
        }
        
        return FileUtils::handle($pathname)->getMetadata();
    }
    
    
    protected string $disk;
    protected string $path;
    protected bool $resultCopy;
    protected ?Throwable $lastThrowable = null;

    
    public static function copy(string $disk, string $path): static {
        $instance = new static();
        
        $instance->disk = $disk;
        $instance->path = $path;
        $instance->resultCopy = false;
        $instance->lastThrowable = null;
        
        return $instance;
    }
    
    
    public function to(string $disk, string $path) {
        $this->resultCopy = false;
        $this->lastThrowable = null;
        
        if (! static::is_file($this->disk, $this->path) ) {
            $this->lastThrowable = new InvalidArgumentException("Source file '{$this->path}' on disk '{$this->disk}' does not exist or is not a file.");
            return $this;
        }
        
        if (! static::checkDisk($disk)) {
            $this->lastThrowable = new InvalidArgumentException("Destination disk '{$disk}' is not configured or invalid.");
            return $this;
        }
        
        $sourcePathname = static::resolvePath($this->disk, $this->path);
        if ($sourcePathname === null) {
            $this->lastThrowable = new RuntimeException("Could not resolve source path for '{$this->path}' on disk '{$this->disk}'.");
            return $this;
        }
        
        try {
            $sourceStream = Storage::disk($this->disk)->readStream($this->path);
            if (!$sourceStream) {
                throw new RuntimeException("Failed to obtain source stream.");
            }
            
            $copied = Storage::disk($disk)
                ->put($path, $sourceStream);
            
            if ($copied) {
                $this->resultCopy = true;
            } else {
                throw new RuntimeException("File copy operation failed unexpectedly.");
            }
            
        } catch (Throwable $e) {
            
            $this->resultCopy = false;
            $this->lastThrowable = $e;
            
            
        } finally {
            
            if (is_resource($sourceStream)) {
                fclose($sourceStream);
            }
            
        }
        
        return $this;
    }
    
    public function withDelete() {
        if ($this->resultCopy && static::exists($this->disk, $this->path)) {
            static::delete($this->disk, $this->path);
        }
        
        return $this;
    }
    
    public function is_success(): bool {
        return $this->resultCopy;
    }
    
    public function getLastThrowable(): Throwable|null {
        return $this->lastThrowable;
    }
    
    
    
}