<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryReservation extends Model
{
    use HasFactory;

    protected $table = 'inventory_reservations';

    public const ST_ACTIVE   = 1; // đang giữ
    public const ST_RELEASED = 2; // đã nhả
    public const ST_CONSUMED = 3; // đã dùng

    protected $fillable = [
        'warehouse_id', 'sku_id', 'quantity',
        'source_document_id', 'source_document_line_id', 'reference_type', 'reference_id',
        'status', 'expires_at', 'note', 'created_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'status' => 'integer',
        'expires_at' => 'datetime',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function sku(): BelongsTo
    {
        return $this->belongsTo(Sku::class, 'sku_id');
    }

    public function sourceDocument(): BelongsTo
    {
        return $this->belongsTo(StockDocument::class, 'source_document_id');
    }
}
