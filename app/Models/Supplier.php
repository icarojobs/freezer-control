<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected function document(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => sanitize($value),
        );
    }
}
