<?php

namespace App\Contracts\Enums;

use Illuminate\Support\Collection;
use UnitEnum;

interface BaseEnumInterface
{
    public static function get_cases(bool $to_array = false): array|Collection;
    public static function get_map(): array;
    public static function get_map_value(): array;
    public static function get_map_name(): array;
    public static function is_valid(string $value): bool;
    public static function has_name(string $name): bool;
    public static function from_name(string $name): ?static;
    public static function from_value(string $value): ?static;
    public static function from_label(string $label): ?static;

    public function label(): string;
}