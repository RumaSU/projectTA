<?php

namespace App\Utils;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class DatabaseUtils {
    
    protected string $connection;
    
    
    private const MAP_OPERATORS = [
        'arithmetic' => [
            '+', '-', '/', '*', '%'
        ], 
        'comparison' => [
            '=', '>', '<', '>=', '<=', '<>'
        ],
        'logical' => [
            'AND', 'OR', 'NOT'
        ]
    ];
    
    private const CONFIG_FILE_DATABASE = 'database';
    private static array $CACHE_CONNECTED = [];
    public $database = null;
    
    
    
    public function __construct(?string $connection = null) {
        $this->connection = static::resolveConnection($connection);
        $this->database = DB::connection($this->connection);
    }
    
    // Static
    // Main static to manipulate database
    public static function connection(?string $connection = null): self{
        return new static($connection);
    }
    
    // static function
    private static function loadConfig() {
        return config(static::CONFIG_FILE_DATABASE);
    }
    public static function configDefaultConnection(): string  {
        return static::loadConfig()['default'];
    }
    public static function configConnections(): array{
        return static::loadConfig()['connections'];
    }
    public static function configConnection(string $connection): array|null {
        return static::configConnections()[$connection] ?? null;
    }
    public static function configMigration(): array {
        return static::loadConfig()['migrations'];
    }
    public static function configRedis(): array {
        return static::loadConfig()['redis'];
    }
    
    public static function checkConnectionSupport(string $connection): bool {
        return array_key_exists($connection, static::configConnections());
    }
    public static function checkConnectionConnected(string $connection): bool {
        if (isset(static::$CACHE_CONNECTED[$connection])) {
            return static::$CACHE_CONNECTED[$connection];
        }
        
        try {
            $connected = DB::connection($connection)->getPdo() !== null;
            static::$CACHE_CONNECTED[$connection] = $connected;
            return $connected;
        } catch (\Throwable $e) {
            LogUtils::log(
                'single',
                "Connection {$connection} not connect",
                [],
                'error'
            );
            static::$CACHE_CONNECTED[$connection] = false;
            return false;
        }
    }
    
    public static function resolveConnection(string|null $connection): string {
        $connection = $connection ?? static::configDefaultConnection();
        
        $original = $connection;
        if (! static::checkConnectionSupport($connection)) {
            $connection = static::configDefaultConnection();
        }
        
        if (! static::checkConnectionConnected($connection)) {
            $connection = static::configDefaultConnection();
        }
        
        if ($connection !== $original) {
            LogUtils::log(
                'single', 
                "Connection '{$original}' not available. Falling back to '{$connection}'.",
                [], 'warning'
            );
        }
        
        return $connection;
    }
    
    
    public static function getConnection(): string {
        return static::configDefaultConnection();
    }
    public static function getListConnectionConnected(): array {
        $connections = array_keys(static::configConnections());
        $connected = [];
        
        foreach ($connections as $connection) {
            $connected[] = [
                'connection' => $connection,
                'connected' => static::checkConnectionConnected($connection)
            ];
        }
        
        return $connected;
    }
    
    // static helper operator
    public static function getOperator() {
        return static::MAP_OPERATORS;
    }
    public static function getAllOperator() {
        return Arr::flatten(static::MAP_OPERATORS);
    }
    public static function getArithmeticOperator() {
        return static::MAP_OPERATORS['arithmetic'];
    }
    public static function getComparisonOperator() {
        return static::MAP_OPERATORS['comparison'];
    }
    public static function getLogicalOperator() {
        return static::MAP_OPERATORS['logical'];
    }
    public static function resolveComparisonOperator(?string $operator = null) {
        $operator = $operator ?? static::MAP_OPERATORS['comparison'][0];
        
        if (static::checkOperator($operator)) {
            return $operator;
        }
        
        return static::MAP_OPERATORS['comparison'][0];
    }
    public static function checkOperator($operator): bool {
        return in_array($operator, Arr::flatten(static::MAP_OPERATORS));
    }
    
    
    
    
    public function where(string $table, ?string $column = null, ?string $value = null, string $operator = '=') {
        $operator = static::resolveComparisonOperator($operator);
        return $this->database
            ->table($table)
            ->where($column, $operator, $value);
    }
    
    public function get(string $table, ?string $column = null, ?string $value = null, string $operator = '=') {
        $query = $this->where($table, $column, $value, $operator);
        return $query ? $query->get() : null;
    }

    public function first(string $table, ?string $column = null, ?string $value = null, string $operator = '=') {
        $query = $this->where($table, $column, $value, $operator);
        return $query ? $query->first() : null;
    }

    public function exists(string $value, string $table, string $column): bool {
        $query = $this->where($table, $column, $value);
        return $query 
            ? $query->exists() 
            : false;
    }
    
    public function delete(string $table, string $column, string $value, string $operator = '=') {
        $query = $this->where($table, $column, $value, $operator);
        return $query ? $query->delete() : 0;
    }
    
    public function getCurrentConnection() {
        return $this->connection;
    }
}