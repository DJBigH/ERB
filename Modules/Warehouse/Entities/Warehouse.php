<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Company\Entities\Branch;
use Modules\Company\Entities\Company;
use Modules\User\Entities\User;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouses';

    /**
     * Schema aligned to the existing `warehouses` table (org-scoped: company_id / branch_id,
     * column `warehouse_type`). Kept as-is to preserve existing data.
     */
    protected $fillable = [
        'company_id',
        'branch_id',
        'parent_id',
        'code',
        'name',
        'warehouse_type',
        'address',
        'manager_id',
        'status',
    ];

    protected $casts = [
        'warehouse_type' => 'integer',
        'status' => 'integer',
    ];

    /**
     * Get the company that owns the warehouse.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the branch that owns the warehouse.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get the parent warehouse (kho cha).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'parent_id');
    }

    /**
     * Get the child warehouses (kho con).
     */
    public function children(): HasMany
    {
        return $this->hasMany(Warehouse::class, 'parent_id');
    }

    /**
     * Get the user in charge of the warehouse (nguoi phu trach).
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get the inventory balances stored in this warehouse.
     */
    public function balances(): HasMany
    {
        return $this->hasMany(InventoryBalance::class, 'warehouse_id');
    }

    /**
     * Get the stock movements that occurred in this warehouse.
     */
    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'warehouse_id');
    }
}
