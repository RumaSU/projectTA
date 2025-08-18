<?php

namespace App\Libraries;

class Common {
    
    public static function formatBytes($bytes, $precision = 2) {
        $units = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= (1 << (10 * $pow)); // 1024^pow
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    
}