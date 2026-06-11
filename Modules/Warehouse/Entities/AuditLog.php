<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Entities\User;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    /**
     * Append-only log: only created_at is maintained (no updated_at).
     */
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'object_type',
        'object_id',
        'detail',
        'ip_address',
        'created_at',
    ];

    protected $casts = [
        'detail' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
