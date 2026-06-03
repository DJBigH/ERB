<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'tax_code',
        'phone',
        'email',
        'address',
        'status',
    ];

    /**
     * Get the company that owns the branch.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the departments of this branch.
     */
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class, 'branch_id');
    }

    /**
     * Get the users belonging to this branch.
     */
    public function users(): HasMany
    {
        return $this->hasMany(\Modules\User\Entities\User::class, 'branch_id');
    }
}
