<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sku extends Model
{
    use HasFactory;

    protected $table = 'skus';

    protected $fillable = [
        'code',
        'name',
        'unit',
        'category',
        'has_serial',
        'status',
    ];

    protected $casts = [
        'has_serial' => 'boolean',
        'status' => 'integer',
    ];

    /**
     * Get the inventory balances for this SKU across warehouses.
     */
    public function balances(): HasMany
    {
        return $this->hasMany(InventoryBalance::class, 'sku_id');
    }

    /**
     * Get the stock movements for this SKU.
     */
    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'sku_id');
    }
}
