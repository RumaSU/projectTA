<?php

namespace App\Enums;

enum AuthField: string {
    case EMAIL = 'email';
    case USERNAME = 'username';
    case PHONE = 'phone';
    
    
    public static function detect(string $value): self {
        return match (true) {
            boolval( filter_var($value, FILTER_VALIDATE_EMAIL) ) => self::EMAIL,
            // preg_match('/^\+?[0-9]{10,15}$/', $value) => self::Phone,
            default => self::USERNAME,
        };
    }
    
}