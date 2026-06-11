<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\User\Entities\User;

class StockDocument extends Model
{
    use HasFactory;

    protected $table = 'stock_documents';

    // ----- Loại phiếu -----
    public const TYPE_INBOUND  = 1;
    public const TYPE_OUTBOUND = 2;
    public const TYPE_TRANSFER = 3;

    // ----- Trạng thái -----
    public const ST_DRAFT      = 1;
    public const ST_PENDING    = 2;
    public const ST_APPROVED   = 3;
    public const ST_COMPLETED  = 4;
    public const ST_IN_TRANSIT = 5;
    public const ST_RETURNED   = 8;
    public const ST_CANCELLED  = 9;

    public const STATUS_LABELS = [
        1 => 'Nháp', 2 => 'Chờ duyệt', 3 => 'Đã duyệt', 4 => 'Hoàn tất',
        5 => 'Đang vận chuyển', 8 => 'Bị trả lại', 9 => 'Đã hủy',
    ];
    public const TYPE_LABELS = [1 => 'Nhập kho', 2 => 'Xuất kho', 3 => 'Điều chuyển'];

    protected $fillable = [
        'code', 'type', 'status', 'source_warehouse_id', 'dest_warehouse_id',
        'note', 'reason', 'created_by', 'approved_by', 'approved_at', 'performed_by', 'performed_at',
    ];

    protected $casts = [
        'type' => 'integer', 'status' => 'integer',
        'approved_at' => 'datetime', 'performed_at' => 'datetime',
    ];

    protected $appends = ['status_label', 'type_label'];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? (string) $this->status;
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_LABELS[$this->type] ?? (string) $this->type;
    }

    public function lines(): HasMany
    {
        return $this->hasMany(StockDocumentLine::class, 'stock_document_id');
    }

    public function sourceWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'source_warehouse_id');
    }

    public function destWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'dest_warehouse_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
