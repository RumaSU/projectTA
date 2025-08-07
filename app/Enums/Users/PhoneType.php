<?php

namespace App\Enums\Users;

enum PhoneType: int {
    
    case PERSONAL = 1;
    case HOME = 2;
    case OFFICE = 3;
    
    
    
    public function getName() {
        return match($this) {
            self::PERSONAL => 'PERSONAL',
            self::HOME => 'HOME',
            self::OFFICE => 'OFFICE'
        };
    }
    
    
}