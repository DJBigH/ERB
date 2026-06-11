<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SafeStockConfig extends Model
{
    use HasFactory;

    protected $table = 'safe_stock_configs';

    protected $fillable = [
        'sku_id', 'warehouse_id', 'min_qty', 'max_qty',
        'effective_from', 'effective_to', 'note', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'min_qty' => 'decimal:2',
        'max_qty' => 'decimal:2',
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    public function sku(): BelongsTo
    {
        return $this->belongsTo(Sku::class, 'sku_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
