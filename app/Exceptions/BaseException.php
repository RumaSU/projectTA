<?php

namespace App\Exceptions;

use App\Utils\LogUtils;
use Monolog\Level;


use Exception;
use Throwable;

class BaseException extends Exception {
    
    protected string $LOG_CHANNEL = 'single';
    protected function log_level(): string {
        return Level::Error->toPsrLogLevel();
    }
    
    
    public function __construct(
        string $message = "", 
        int $code = 0, 
        Throwable|null $previous = null,
        bool $autoLog = true
        ) {
        
        parent::__construct($message, $code, $previous);
        
        if ($autoLog) {
            $this->log();
        }
    }
    
    public function getLogChannel(): string {
        return $this->LOG_CHANNEL;
    }
    
    public function getLogLevel(): string {
        return $this->log_level();
    }
    
    protected function log() {
        LogUtils::logException(
            $this,
            $this->getLogChannel(),
            $this->getLogLevel(),
            $this->logTag(),
            false
        );
    }
    
    protected function logTag() {
        return class_basename(static::class);
    }
    
}