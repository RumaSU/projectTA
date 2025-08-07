<?php

namespace App\Utils;

use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;
use Monolog\Level;
use Throwable;

use App\Libraries\UuidHelper;
use Carbon\Carbon;

class LogUtils {
    
    private const CONFIG_FILE_LOGGING = 'logging';
    
    private const CONFIG_CHANNEL_TRACE = 'log_trace';
    private const TRACE_TAKE = 5;
    private const TRACE_DEBUG_TAKE = 10;
    
    public static function getConfig() {
        return config(static::CONFIG_FILE_LOGGING);
    }
    
    public static function getChannels() {
        return static::getConfig()['channels'] ?? null;
    }
    
    public static function getChannel(string $channel) {
        return static::getChannels()[$channel] ?? null;
    }
    public static function getDefaultChannel() {
        return static::getConfig()['default'] ?? null;
    }
    
    public static function getLevels(): array {
        return array_map('strtolower', Level::NAMES);
    }
    public static function getDefaultLevel():string  {
        return static::getLevels()[0];
    }
    
    public static function resolveChannel(string $channel) {
        return static::channelExists($channel)
            ? $channel
            : static::getDefaultChannel();
    }
    
    public static function resolveLevel(string $level) {
        $level = strtolower($level);
        return static::levelExists($level)
            ? $level
            : static::getDefaultLevel();
    }
    
    
    public static function channelExists(string $channel): bool {
        return array_key_exists($channel, static::getChannels());
    }
    public static function levelExists(string $level) {
        return in_array($level, static::getLevels());
    }
    
    
    public static function log(string $channel, string $message, array $context = [], string $level = 'info', string|null $tag = null, bool $add_identifier = true) {
        if (! static::channelExists($channel)) {
            $channel = static::getDefaultChannel();
            $message = "[Fallback] Channel `$channel` not found. Using default: " . $message;
        }
        
        $level = static::resolveLevel($level);
        $l = Log::channel($channel);
        
        $context = $add_identifier
            ? array_merge($context, static::addIdentifier($channel, $level))
            : $context;
        
        $tag = strtoupper($tag ?? $level);
        
        $message = "[". $tag ."] " . $message;
        static::write($message, $context, $level, $l);
    }
    
    public static function logException(Throwable $e, string|null $channel = null, string $level = 'error', string|null $tag = null, bool $add_identifier = false) {
        $channel = $channel 
            ? static::resolveChannel($channel) 
            : static::getDefaultChannel();
        
        $level = static::levelExists($level)
            ? $level
            : Level::Error->toPsrLogLevel();
        
        $message = $e->getMessage();
        $context = [
            'class' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'code' => $e->getCode(),
            'trace' => collect($e->getTrace())->take(static::TRACE_TAKE),
        ];
        
        static::logTrace($e);
        static::log($channel,  $message, $context, $level, $tag, $add_identifier);
    }
    
    private static function logTrace(Throwable $e) {
        $channel = static::resolveChannel(static::CONFIG_CHANNEL_TRACE);
        $message = $e->getMessage();
        $context = [
            'class' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'code' => $e->getCode(),
            'trace' => collect($e->getTrace())->take(static::TRACE_DEBUG_TAKE)
        ];
        
        static::log($channel, $message, $context, Level::Error->toPsrLogLevel(), null, false);
    }
    
    private static function write(string $message, array $context, string $level = 'info', LoggerInterface $l) {
        $l->{$level}($message, $context);
    }
    
    private static function addIdentifier(string $channel, string $level) {
        return [
            "utils_identifier" => [
                'log_id' => UuidHelper::generate('v4')->toString(),
                'log_class_utils' => static::class,
                'log_timestamp' => Carbon::now()->toIso8601String(),
                'log_env' => app()->environment(),
                'log_channel_used' => $channel,
                'log_level_used' => $level,
                'log_origin' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, static::TRACE_DEBUG_TAKE) ?? null
            ]
        ];
    }

}