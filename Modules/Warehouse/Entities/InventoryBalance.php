<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryBalance extends Model
{
    use HasFactory;

    protected $table = 'inventory_balances';

    protected $fillable = [
        'warehouse_id',
        'sku_id',
        'available_qty',
        'in_transit_qty',
        'reserved_qty',
    ];

    protected $casts = [
        'available_qty' => 'decimal:2',
        'in_transit_qty' => 'decimal:2',
        'reserved_qty' => 'decimal:2',
    ];

    /**
     * On-hand stock = available + in-transit (BR-34, suy ra - khong luu cot rieng).
     */
    public function getOnHandQtyAttribute(): string
    {
        return bcadd((string) $this->available_qty, (string) $this->in_transit_qty, 2);
    }

    /**
     * Get the warehouse that owns the balance.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    /**
     * Get the SKU of the balance.
     */
    public function sku(): BelongsTo
    {
        return $this->belongsTo(Sku::class, 'sku_id');
    }
}
