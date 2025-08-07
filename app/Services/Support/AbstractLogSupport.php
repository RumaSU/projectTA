<?php

namespace App\Services\Support;

use Throwable;

abstract class AbstractLogSupport {
    
    protected static string $LOG_CHANNEL_NAME;
    
    public static function setChannel(string $channel): void {
        static::$LOG_CHANNEL_NAME = $channel;
    }
    public static function getChannel(): string {
        return static::$LOG_CHANNEL_NAME;
    }
    
    abstract public static function log(
        string $message,
        array $context = [],
        string $level = 'info',
        string|null $tag = null,
        bool $add_identifier = false
    ): void;
    
    abstract public static function logException(
        Throwable $e, 
        string $level = 'error', 
        string|null $tag = null, 
        bool $add_identifier = false
    ): void;
    
}