<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryReportSnapshot extends Model
{
    use HasFactory;

    protected $table = 'inventory_report_snapshots';
    public $timestamps = false;

    protected $fillable = [
        'snapshot_date', 'warehouse_id', 'sku_id',
        'available_qty', 'in_transit_qty', 'reserved_qty', 'created_at',
    ];

    protected $casts = [
        'snapshot_date' => 'date',
        'available_qty' => 'decimal:2',
        'in_transit_qty' => 'decimal:2',
        'reserved_qty' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function sku(): BelongsTo
    {
        return $this->belongsTo(Sku::class, 'sku_id');
    }
}
