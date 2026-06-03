<?php

namespace Modules\User\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $table = 'users';

    protected $fillable = [
        'company_id',
        'branch_id',
        'department_id',
        'position_id',
        'role_id',
        'code',
        'name',
        'email',
        'phone',
        'password',
        'gender',
        'birthday',
        'address',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday' => 'date',
        'password' => 'hashed',
    ];

    /**
     * Get the company of the user.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(\Modules\Company\Entities\Company::class, 'company_id');
    }

    /**
     * Get the branch of the user.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(\Modules\Company\Entities\Branch::class, 'branch_id');
    }

    /**
     * Get the department of the user.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(\Modules\Company\Entities\Department::class, 'department_id');
    }

    /**
     * Get the position of the user.
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(\Modules\Company\Entities\Position::class, 'position_id');
    }

    /**
     * Get the role of the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
