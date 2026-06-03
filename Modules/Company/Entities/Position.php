<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    use HasFactory;

    protected $table = 'positions';

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the users with this position.
     */
    public function users(): HasMany
    {
        return $this->hasMany(\Modules\User\Entities\User::class, 'position_id');
    }
}
