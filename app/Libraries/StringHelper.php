<?php

namespace App\Libraries;

class StringHelper {
    public static function random(int $length = 8, bool $upper = true, bool $lower = true, bool $number = true, bool $symbol = false) {
        $characters = '';
        if ($upper) $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($lower) $characters .= 'abcdefghijklmnopqrstuvwxyz';
        if ($number) $characters .= '0123456789';
        if ($symbol) $characters .= "!@#$%^&*()-_=+[]{};:,.<>?/|~`";
        
        return substr(str_shuffle($characters), 0, $length);
    }
    
    public static function contains(string $haystack, array|string $needles, bool $all = true): bool {
        $needles = (array) $needles;
        
        if ($all) {
            foreach ($needles as $needle) {
                if (! str_contains($haystack, $needle)) {
                    return false;
                }
            }
            return true;
        } else {
            foreach ($needles as $needle) {
                if (str_contains($haystack, $needle)) {
                    return true;
                }
            }
            return false;
        }
    }
}