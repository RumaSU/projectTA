<?php

namespace App\Utils\ApplicationPath;

use App\Libraries\ApplicationPathHelper;

enum AppPath: string
{
    
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
    
    public static function root() {
        return ApplicationPathHelper::app();
    }
    
    public function section() {
        return ApplicationPathHelper::app($this->value);
    }
    
    public function get(string $path = ''): string
    {
        return ApplicationPathHelper::app(
            $this->value . ($path ? '/' . ltrim($path, '/') : '')
        );
    }
    
}