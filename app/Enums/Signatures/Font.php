<?php

namespace App\Enums\Signatures;

use App\Contracts\Enums\HasAssociatedModelEnum;
use App\Trait\InteractWithModelEnum;
use App\Trait\InteractWithBaseEnum;

enum Font: string implements HasAssociatedModelEnum {
    use InteractWithBaseEnum, InteractWithModelEnum;
    
    
    // [ "font" => "--main-font-pacifico", "text" => "Pacifico", "value" => "Pacifico, cursive", "default" => true, ],
    // [ "font" => "--main-font-dancing-script", "text" => "Dancing Script", "value" => "Dancing Script, cursive", "default" => false, ],
    // [ "font" => "--main-font-great-vibes", "text" => "Great Vibes", "value" => "Great Vibes, cursive", "default" => false, ],
    // [ "font" => "--main-font-satisfy", "text" => "Satisfy", "value" => "Satisfy, cursive", "default" => false, ],
    // [ "font" => "--main-font-allura", "text" => "Allura", "value" => "Allura, cursive", "default" => false, ],
    // [ "font" => "--main-font-alex-brush", "text" => "Alex Brush", "value" => "Alex Brush, cursive", "default" => false, ],
    // [ "font" => "--main-font-signika", "text" => "Signika", "value" => "Signika", "default" => false, ],
    // [ "font" => "--main-font-mr-dafoe", "text" => "Mr Dafoe", "value" => "Mr Dafoe, cursive", "default" => false, ],
    // [ "font" => "--main-font-homemad-apple", "text" => "Homemad Apple", "value" => "Homemade Apple, cursive", "default" => false, ],
    
    
    case PACIFICO = 'Pacifico, cursive';
    case DANCING_SCRIPT = 'Dancing Script, cursive';
    case GREAT_VIBES = 'Great Vibes, cursive';
    case SATISFY = 'Satisfy, cursive';
    case ALLURA = 'Allura, cursive';
    case ALEX_BRUSH = 'Alex Brush, cursive';
    case SIGNIKA = 'Signika';
    case MR_DAFOE = 'Mr Dafoe, cursive';
    case HOMEMADE_APPLE = 'Homemade Apple, cursive';
    
    public static function model_class_name(): string {
        return \App\Models\Signatures\SignatureDrawings::class;
    }
    
    public static function get_default(): string {
        return static::PACIFICO->value;
    }
    
    public static function get_default_name(): string {
        return static::PACIFICO->label();
    }
    
    /** 
     * @return array<array{font: string, text: string, value: string, default: bool}>
     */
    public static function get_mapped_fonts(): array {
        $mapped = [];
        
        foreach(static::cases() as $case) {
            $mapped[] = [
                "font" => $case->font(), 
                "text" => $case->label(), 
                "value" => $case->value, 
                "default" => $case->value === static::get_default()
            ];
        }
        
        return $mapped;
    }

    public function label(): string {
        return match($this) {
            static::PACIFICO => 'Pacifico',
            static::DANCING_SCRIPT => 'Dancing Script',
            static::GREAT_VIBES => 'Great Vibes',
            static::SATISFY => 'Satisfy',
            static::ALLURA => 'Allura',
            static::ALEX_BRUSH => 'Alex Brush',
            static::SIGNIKA => 'Signika',
            static::MR_DAFOE => 'Mr Dafoe',
            static::HOMEMADE_APPLE => 'Homemade Apple'
        };
    }
    
    public function font(): string {
        return match($this) {
            static::PACIFICO => '--main-font-pacifico',
            static::DANCING_SCRIPT => '--main-font-dancing-script',
            static::GREAT_VIBES => '--main-font-great-vibes',
            static::SATISFY => '--main-font-satisfy',
            static::ALLURA => '--main-font-allura',
            static::ALEX_BRUSH => '--main-font-alex-brush',
            static::SIGNIKA => '--main-font-signika',
            static::MR_DAFOE => '--main-font-mr-dafoe',
            static::HOMEMADE_APPLE => '--main-font-homemad-apple'
        };
    }
    
    
    
    
    
}