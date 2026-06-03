<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [
        'name',
        'code',
        'tax_code',
        'phone',
        'email',
        'address',
        'status',
        'parent_id',
    ];

    /**
     * Get the parent company.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'parent_id');
    }

    /**
     * Get the sub-companies.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Company::class, 'parent_id');
    }

    /**
     * Get the branches of this company.
     */
    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class, 'company_id');
    }

    /**
     * Get the users belonging to this company.
     */
    public function users(): HasMany
    {
        return $this->hasMany(\Modules\User\Entities\User::class, 'company_id');
    }
}
