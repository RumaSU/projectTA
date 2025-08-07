<?php

namespace App\Enums;

use App\Contracts\Enums\BaseEnumInterface;
use App\Contracts\Enums\HasDefaultEnum;
use App\Trait\InteractWithBaseEnum;

enum AppPath: string implements BaseEnumInterface, HasDefaultEnum{
    use InteractWithBaseEnum;
    
    case ACTIONS = 'Actions';
    case CONSOLE = 'Console';
    case EVENTS = 'Events';
    case HTTP = 'Http';
    case CONTROLLERS = 'Http/Controllers';
    case MIDDLEWARE = 'Http/Middleware';
    case JOBS = 'Jobs';
    case LIBRARIES = 'Libraries';
    case LIVEWIRE = 'Livewire';
    case MODELS = 'Models';
    case PROVIDERS = 'Providers';
    case SERVICES = 'Services';
    case UTILS = 'Utils';
    
    public static function get_default(): string {
        // return static::APP->value;
        return '';
    }
    
    public static function get_default_name(): string {
        // return static::APP->label();
        return '';
    }
    
    public function label(): string {
        return match($this) {
            // static::APP => ucfirst(strtolower(static::INFO->name)),
            // static::SUCCESS => ucfirst(strtolower(static::SUCCESS->name)),
            // static::WARNING => ucfirst(strtolower(static::WARNING->name)),
            // static::DANGER => ucfirst(strtolower(static::DANGER->name)),
            default => ''
        };
    }
    
    // public static function root() {
    //     return ApplicationPathHelper::app();
    // }
    
    // public function section() {
    //     return ApplicationPathHelper::app($this->value);
    // }
    
    // public function get(string $path = ''): string
    // {
    //     return ApplicationPathHelper::app(
    //         $this->value . ($path ? '/' . ltrim($path, '/') : '')
    //     );
    // }
    
}