<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Entities\User;

class DocumentStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'document_status_history';
    public $timestamps = false;

    protected $fillable = ['stock_document_id', 'from_status', 'to_status', 'changed_by', 'changed_at', 'note'];

    protected $casts = [
        'from_status' => 'integer',
        'to_status' => 'integer',
        'changed_at' => 'datetime',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(StockDocument::class, 'stock_document_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
