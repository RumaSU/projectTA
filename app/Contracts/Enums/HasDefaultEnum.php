<?php

namespace App\Contracts\Enums;

interface HasDefaultEnum {
    
    public static function get_default(): string;
    public static function get_default_name(): string;
    
}