<?php

namespace App\Libraries;

class ApplicationPathHelper {
    
    public static function base(string $path = ''): string {
        return base_path($path);
    }
    
    public static function app(string $path = ''): string {
        return app_path($path);
    }
    
    public static function bootstrap(string $path = '') {
        return static::base( 'bootstrap' . ($path ? '/' . ltrim($path, '/') : '' ) );
    }
    
    public static function config(string $path = ''): string {
        return config_path($path);
    }
    
    public static function database(string $path = ''): string {
        return database_path($path);
    }
    
    public static function lang(string $path = ''): string {
        return lang_path($path);
    }
    
    public static function public(string $path = ''): string {
        return public_path($path);
    }
    
    public static function resource(string $path = ''): string {
        return resource_path($path);
    }
    
    public static function storage(string $path = ''): string {
        return storage_path($path);
    }
}