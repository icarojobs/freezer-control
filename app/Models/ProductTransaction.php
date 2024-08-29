<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProductTransactionTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductTransaction extends Model
{
    use HasFactory;

    protected $casts = [
        'type' => ProductTransactionTypeEnum::class,
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class)->where('status', 1);
    }

    public function scopeApplyFirstQueryScopeGetSale($query)
    {
        return $query->where('type', ProductTransactionTypeEnum::SALE->value);
    }
}
