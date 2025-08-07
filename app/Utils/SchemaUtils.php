<?php

namespace App\Utils;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Builder;
use App\Utils\DatabaseUtils;

class SchemaUtils {
    
    public static function schemaBuilder(?string $connection = null): Builder {
        $connection = DatabaseUtils::resolveConnection($connection);
        return Schema::connection($connection);
    }
    
    // Get Schema
    public static function getTables(?string $connection = null, bool $returnNamesOnly = false) {
        $connection = DatabaseUtils::resolveConnection($connection);
        $tables = static::schemaBuilder($connection)->getTables();
        if ( $returnNamesOnly ) {
            return collect($tables)
                ->map(function($table) {
                    return $table->name;
                })->all();
        }
        
        return $tables;
    }
    public static function getColumns(string|null $connection = null, string $table, array $propertiesToExtract = [], bool $returnAsObjects = false) {
        $connection = DatabaseUtils::resolveConnection($connection);
        if (! static::checkTable($table, $connection) ) {
            return null;
        }
        
        $columns = static::schemaBuilder($connection)->getColumns($table);
        if (empty($propertiesToExtract)) {
            return $columns;
        }
        
        return collect($columns)
            ->map(function($column) use ($propertiesToExtract, $returnAsObjects) {
                $extracted = [];
                foreach($propertiesToExtract as $prop) {
                    if ( property_exists($column, $prop) ) {
                        $extracted[$prop] = $column->$prop;
                    }
                }
                
                return $returnAsObjects
                    ? (object) $extracted
                    : $extracted;
                
            })->all();
    }
    
    public static function getColumnsName(?string $connection = null, string $table) {
        $connection = DatabaseUtils::resolveConnection($connection);
        if (! static::checkTable($table, $connection) ) {
            return null;
        }
        
        $columns = static::schemaBuilder($connection)->getColumns($table);
        return collect($columns)
            ->map(function($column) {
                return $column->name;
            })->all();
    }
    
    public static function getTableColumnsMap($connection) {
        $connection = DatabaseUtils::resolveConnection($connection);
        
        $tables = static::getTables($connection, true);
        $columnsMap  = [];
        foreach($tables as $table) {
            
            $columns = static::getColumns($connection, $table);
            if ($columns === null) continue;
            
            $columnsMap [] = [
                'table' => $table,
                'column' => $columns 
            ];
            
        }
        
        return $columnsMap ;
    }
    
    // Check
    public static function checkTable(string $table, ?string $connection = null): bool {
        $connection = DatabaseUtils::resolveConnection($connection);
        
        return static::schemaBuilder($connection)->hasTable($table);
    }
    
    public static function checkColumn(string $table, string $column, ?string $connection = null): bool {
        $connection = DatabaseUtils::resolveConnection($connection);
        
        return static::schemaBuilder($connection)->hasColumn($table, $column);
    }
    
    public static function checkColumns(string $table, array $columns, ?string $connection = null): bool {
        $connection = DatabaseUtils::resolveConnection($connection);
        $existingColumns  = static::getColumnsName($connection, $table);
        
        if ($existingColumns  === null) {
            LogUtils::log(
                'single',
                'Table not found: ' . $table,
                []
            );
            return false;
        }
        
        $missing = array_diff($columns, $existingColumns);
        
        return empty($missing);
    }
}