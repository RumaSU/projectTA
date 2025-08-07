<?php

namespace App\Utils;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Utils\DatabaseUtils;

class QueryExceptionUtils {
    
    protected string $connection;
    protected QueryException|null $qException = null;
    protected array $config;
    
    protected const SUPPORT_DRIVER_MAP = [
        'mysql' => \App\Utils\QueryException\MysqlErrorReference::class
    ];
    
    protected const SQLSTATE_SAFE_RETRY = [ 
        "40001" => "Serialization failure (deadlock)",
        "HY000" => "General error",
        "08S01" => "Communication link failure",
        "08006" => "Connection failure",
        "08003" => "Connection does not exist",
        "08000" => "Connection exception",
        "08001" => "SQL-client unable to establish SQL-connection",
        "57P01" => "Admin shutdown (PostgreSQL)",
        "53300" => "Too many connections (PostgreSQL)",
        "HYT00" => "Timeout expired",
        "HYT01" => "Connection timeout"
    ];
    
    public function __construct($connection, QueryException $e) {
        
        $this->connection = DatabaseUtils::checkConnectionSupport($connection)
            ? $connection
            : DatabaseUtils::configDefaultConnection();
        $this->config = config('database.connections');
        $this->qException = $e;
        
    }
    
    public static function handle($connection, QueryException $e) {
        return new static($connection, $e);
    }
    public static function getDefaultConnection() {
        return DatabaseUtils::configDefaultConnection();
    }
    
    
    
    public function getNowConnection() {
        return $this->connection;
    }
    
    public function getErrorInfo() {
        return $this->qException->errorInfo;
    }
    public function getErrorStateCode() {
        return $this->qException->errorInfo[0] ?? '';
    }
    public function getErrorDriverCode() {
        return $this->qException->errorInfo[1] ?? '';
    }
    public function getErrorMessage() {
        return $this->qException->errorInfo[2] ?? $this->getMessage();
    }
    
    
    public function getMessage() {
        return $this->qException->getMessage();
    }
    
    public function getSql() {
        return $this->qException->getSql();
    }
    
    public function getBindings() {
        return $this->qException->getBindings();
    }
    
    public function getTrace(bool $as_string = false) {
        return $as_string
            ? $this->qException->getTrace()
            : $this->qException->getTraceAsString();
    }

    
    
    
    public function getDriverErrorDetail() {
        $code = $this->getErrorDriverCode();
        $resolver = self::SUPPORT_DRIVER_MAP[$this->connection] ?? null;
        
        return $resolver && method_exists($resolver, 'find')
            ? $resolver::find($code)
            : null;
    }
    
    
    protected function isRetryableByMessage(string $message): bool {
        $lower = strtolower($message);
        return str_contains($lower, 'deadlock') ||
            str_contains($lower, 'lock wait timeout') ||
            str_contains($lower, 'lost connection') ||
            str_contains($lower, 'server has gone away') ||
            str_contains($lower, 'could not connect') ||
            str_contains($lower, 'connection timed out');
    }
    
    public function getSafeRetryReason(): string|null {
        $state = $this->getErrorStateCode();
        $message = $this->getErrorMessage();
        
        if (isset(static::SQLSTATE_SAFE_RETRY[$state])) {
            return static::SQLSTATE_SAFE_RETRY[$state];
        }
        
        if ($state === 'HY000' && $this->isRetryableByMessage($message)) {
            return "Retryable based on error message: " . $message;
        }
        
        return null;
    }
    
    public function shouldRetry(): bool {
        return $this->getSafeRetryReason() !== null;
    }
    
}