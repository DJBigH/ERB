<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InTransitLedger extends Model
{
    use HasFactory;

    protected $table = 'in_transit_ledger';

    public const ST_IN_TRANSIT = 1;
    public const ST_COMPLETED  = 2;
    public const ST_RETURNED   = 3;

    protected $fillable = [
        'stock_document_id', 'stock_document_line_id', 'sku_id',
        'source_warehouse_id', 'dest_warehouse_id',
        'qty_dispatched', 'qty_received', 'qty_returned',
        'dispatched_at', 'received_at', 'status',
    ];

    protected $casts = [
        'qty_dispatched' => 'decimal:2',
        'qty_received' => 'decimal:2',
        'qty_returned' => 'decimal:2',
        'dispatched_at' => 'datetime',
        'received_at' => 'datetime',
        'status' => 'integer',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(StockDocument::class, 'stock_document_id');
    }

    public function sku(): BelongsTo
    {
        return $this->belongsTo(Sku::class, 'sku_id');
    }

    public function sourceWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'source_warehouse_id');
    }

    public function destWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'dest_warehouse_id');
    }
}
