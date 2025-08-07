<?php

namespace App\Enums;

use Illuminate\Support\Str;

use Ramsey\Uuid\Uuid as RamseyUuid;

use App\Contracts\Enums\BaseEnumInterface;
use App\Contracts\Enums\HasDefaultEnum;

use App\Trait\InteractWithBaseEnum;
use Ramsey\Uuid\UuidInterface;

enum Uuid: string implements BaseEnumInterface, HasDefaultEnum {
    use InteractWithBaseEnum;
    
    case V1 = 'v1';
    case V2 = 'v2';
    case V3 = 'v3';
    case V4 = 'v4';
    case V5 = 'v5';
    case V6 = 'v6';
    case V7 = 'v7';
    
    
    
    public static function get_default(): string {
        return static::V4->value;
    }
    
    public static function get_default_name(): string {
        return static::V4->name;
    }
    
    public function get_default_uuid(bool $as_string = false): string|UuidInterface {
        return static::V4->generate(null, $as_string);
    }
    
    public function label(): string {
        return $this->name;
    }
    
    
    public function generate(?string $hash = null, bool $as_string = false): string|UuidInterface {
        $hash = $hash ?? Str::random(32);
        $interface = match($this) {
            static::V1 => RamseyUuid::uuid1(),
            static::V2 => RamseyUuid::uuid2(RamseyUuid::DCE_DOMAIN_PERSON),
            static::V3 => RamseyUuid::uuid3(RamseyUuid::NAMESPACE_URL, $hash),
            static::V4 => RamseyUuid::uuid4(),
            static::V5 => RamseyUuid::uuid5(RamseyUuid::NAMESPACE_URL, $hash),
            static::V6 => RamseyUuid::uuid6(),
            static::V7 => RamseyUuid::uuid7(),
        };
        
        return $as_string
            ? $interface->toString()
            : $interface;
    }
    
}