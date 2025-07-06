<?php

namespace App\Library\Utils;

use App\Library\Helper as LibHelper;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;


class SchemaUtils {
    
    public static function checkColumnExists($table, $column, $connection = null) {
        if (! $connection) $connection = config('database.default');
        if (! self::checkTable($table)) return null;
        return Schema::connection($connection)->hasColumns($table, [$column]);
    }
    
    public static function checkTable($table, $connection = null) {
        if (! $connection) $connection = config('database.default');
        return Schema::connection($connection)->hasTable($table);
    }
    
}