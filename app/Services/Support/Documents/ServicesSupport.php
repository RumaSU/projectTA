<?php

namespace App\Services\Support\Documents;

use App\Utils\ModelUtils;

class ServicesSupport {
    
    protected const PREFIX_MODEL = 'document';
    protected const LOG_CHANNEL = "log_documents";
    protected static array $MAP_MODELS_DOCUMENT = [];
    protected static array $CONFIG = [];
    
    protected static function loadMap() {
        if (! empty(static::$MAP_MODELS_DOCUMENT)) return;
        static::$MAP_MODELS_DOCUMENT = ModelUtils::getModelInfoPrefix(static::PREFIX_MODEL);
    }
    protected static function loadConfig() {
        if (! empty(static::$CONFIG)) return;
        static::$CONFIG = config('config_document');
    }
    
    
    public static function getConfig(string $key = '') {
        static::loadConfig();
        return static::$CONFIG[$key] ?? null;
    }
    public static function getConfigAccept() {
        return static::getConfig('accept') ?? null;
    }
    public static function getConfigLimit() {
        return static::getConfig('limit') ?? 0;
    }
    
    
    public static function checkModel($key) {
        static::loadMap();
        return array_key_exists($key, static::$MAP_MODELS_DOCUMENT);
    }
    
    public static function checkAccept(string $accept) {
        return in_array($accept, static::getConfigAccept()) ?? false;
    }
    public static function checkLimit($size) {
        $c = static::getConfigLimit();
        return $size <= $c;
    }
    
}