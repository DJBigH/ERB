<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IdempotencyKey extends Model
{
    use HasFactory;

    protected $table = 'idempotency_keys';
    public $timestamps = false;

    public const ST_PROCESSING = 1;
    public const ST_DONE       = 2;
    public const ST_FAILED     = 3;

    protected $fillable = [
        'idem_key', 'operation', 'status', 'response_code', 'response_body', 'locked_until', 'created_at',
    ];

    protected $casts = [
        'status' => 'integer',
        'response_body' => 'array',
        'locked_until' => 'datetime',
        'created_at' => 'datetime',
    ];
}
