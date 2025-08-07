<?php

namespace App\Services\Support;

use App\Utils\LogUtils;
use Throwable;

class JobServiceSupport {
    
    public const LOG_JOB_NAME = 'jobs_log';
    
    public static function log(string $message, array $context = [], string $level = 'info', string|null $tag = null, bool $add_identifier = false) {
        
        LogUtils::log(static::LOG_JOB_NAME, $message, $context, $level, $tag, $add_identifier);
        
    }
    
    public static function logException(Throwable $e, string $level = 'error', string|null $tag = null, bool $add_identifier = false) {
        
        LogUtils::logException($e, static::LOG_JOB_NAME, $tag,  $level, $add_identifier);
        
    }
    
}