<?php

namespace App\Enums\Traits;

trait Comparable
{
    public function is(mixed $value): bool
    {
        if (! $value instanceof $this) {
            return false;
        }

        $selfValue = $this->value ?? $this->name;
        $compareValue = $value->value ?? $value->name;

        return $selfValue === $compareValue;
    }

    public function isNot(mixed $value): bool
    {
        return ! $this->is($value);
    }
}
