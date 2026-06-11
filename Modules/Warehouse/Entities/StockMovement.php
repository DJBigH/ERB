<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Entities\User;

class StockMovement extends Model
{
    use HasFactory;

    protected $table = 'stock_movements';

    protected $fillable = [
        'code',
        'source_document_type',
        'source_document_id',
        'movement_type',
        'warehouse_id',
        'sku_id',
        'quantity',
        'qty_before',
        'qty_after',
        'performed_by',
        'idempotency_key',
        'is_reversed',
        'reversed_by_movement_id',
        'reversal_reason',
        'reversal_type',
    ];

    protected $casts = [
        'movement_type' => 'integer',
        'quantity' => 'decimal:2',
        'qty_before' => 'decimal:2',
        'qty_after' => 'decimal:2',
        'is_reversed' => 'boolean',
    ];

    /**
     * Get the warehouse where the movement occurred.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    /**
     * Get the SKU affected by the movement.
     */
    public function sku(): BelongsTo
    {
        return $this->belongsTo(Sku::class, 'sku_id');
    }

    /**
     * Get the user who performed the movement.
     */
    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    /**
     * Get the reversing movement (if this one has been reversed).
     */
    public function reversedBy(): BelongsTo
    {
        return $this->belongsTo(StockMovement::class, 'reversed_by_movement_id');
    }
}
