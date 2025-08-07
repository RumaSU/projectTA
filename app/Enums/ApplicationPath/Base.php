<?php

namespace App\Enums\ApplicationPath;

use App\Contracts\Enums\BaseEnumInterface;
use App\Contracts\Enums\HasDefaultEnum;
use App\Trait\InteractWithBaseEnum;

enum Base: string implements BaseEnumInterface, HasDefaultEnum {
    use InteractWithBaseEnum;
    
    case BASE = 'base';
    case APP = 'app';
    case BOOTSTRAP = 'bootstrap';
    case CONFIG = 'config';
    case DATABASE = 'database';
    case LANG = 'lang';
    case PUBLIC = 'public';
    case RESOURCE = 'resource';
    case STORAGE = 'storage';
    
    public static function get_default_case() {
        return static::BASE;
    }
    
    public static function get_default(): string {
        return static::get_default_case()->value;
    }
    
    public static function get_default_name(): string {
        return static::get_default_case()->name;
    }
    
    public static function get_default_path(): string {
        return static::get_default_case()->root_path();
    }
    
    public static function get_default_func(): string {
        return static::get_default_case()->func();
    }
    
    public function label(): string {
        return ucfirst(strtolower($this->name));
    }
    
    public function root_path(): string {
        return match($this) {
            static::BASE => base_path(),
            static::APP => app_path(),
            static::BOOTSTRAP => $this->bootstrap(),
            static::CONFIG => config_path(),
            static::DATABASE => database_path(),
            static::LANG => lang_path(),
            static::PUBLIC => public_path(),
            static::RESOURCE => resource_path(),
            static::STORAGE => storage_path()
        };
    }
    
    public function func(): ?string {
        return match($this) {
            static::BASE => 'base_path',
            static::APP => 'app_path',
            static::CONFIG => 'config_path',
            static::DATABASE => 'database_path',
            static::LANG => 'lang_path',
            static::PUBLIC => 'public_path',
            static::RESOURCE => 'resource_path',
            static::STORAGE => 'storage_path',
            default => null
        };
    }
    
    public function path_exists($path = ''): bool {
        $func = $this->func();
        if (!$func || !function_exists($func)) {
            return false;
        }
        
        return file_exists($func($path));
    }
    
    
    
    
    private function bootstrap($path = ''): string {
        return base_path('bootstrap' . '/' . ltrim($path, '/'));
    }
}