<?php

namespace App\Trait;

use Illuminate\Support\Collection;
use UnitEnum;
use RuntimeException;

/**
 * @mixin \App\Contracts\Enums\BaseEnumInterface
 */
trait InteractWithBaseEnum {
    
    public static function get_cases(bool $to_array = false): array|Collection {
        if (!is_subclass_of(static::class, UnitEnum::class)) {
            throw new RuntimeException(static::class . ' must be an enum.');
        }
        
        $collection = collect(static::cases());
        
        return $to_array
            ? $collection->toArray()
            : $collection;
    }
    
    public static function get_map(): array {
        return static::get_cases()
            ->pluck('value', 'name')
            ->toArray();
    }
    public static function get_map_value(): array {
        return static::get_cases()
            ->pluck('value')
            ->toArray();
    }
    public static function get_map_name(): array {
        return static::get_cases()
            ->pluck('name')
            ->toArray();
    }
    
    
    public static function is_valid(string $value): bool {
        return in_array($value, static::get_map(), true);
    }
    
    public static function has_name(string $name): bool {
        return in_array($name, static::get_map_name(), true);
    }
    
    public static function from_name(string $name): ?static {
        return static::tryFrom(static::get_map()[$name] ?? null);
    }
    public static function from_value(string $value): ?static {
        return static::tryFrom($value);
    }
    
    public static function from_label(string $label): ?static {
        foreach (static::cases() as $case) {
            if (method_exists($case, 'label') && $case->label() === $label) {
                return $case;
            }
        }
        return null;
    }
    
}