<?php

namespace App\Enums\Traits;

trait Valuable
{
    use Comparable;

    /**
     * @return array<int, mixed>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array<int, mixed>
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function toArray(): array
    {
        return array_combine(self::values(), self::names());
    }
}
