<?php

namespace App\Libraries;

class TerminalHelper {
    
    public static function getTerminalWidth(): int {
        if ($envCols = getenv('COLUMNS')) {
            return (int) $envCols;
        }
        
        if (stripos(PHP_OS, 'Linux') !== false || stripos(PHP_OS, 'Darwin') !== false) {
            $output = [];
            exec('tput cols 2>/dev/null', $output);
            if (!empty($output) && is_numeric($output[0])) {
                return (int) $output[0];
            }
        }
        
        if (DIRECTORY_SEPARATOR === '\\') {
            $output = [];
            exec('mode con', $output);
            foreach ($output as $line) {
                if (preg_match('/\s*Columns:\s+(\d+)/i', $line, $matches)) {
                    return (int) $matches[1];
                }
            }
        }
        
        return 80;
    }

    
    
}