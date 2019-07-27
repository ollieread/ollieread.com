<?php

namespace Ollieread\Users\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as BaseUser;

/**
 * Class User
 *
 * @property-read int                                 $id
 * @property string                                   $api_key
 * @property string|null                              $name
 * @property string                                   $username
 * @property string                                   $email
 * @property string|null                              $password
 * @property string|null                              $remember_token
 * @property string|null                              $avatar
 * @property bool                                     $active
 * @property bool                                     $verified
 * @property \Carbon\Carbon                           $created_at
 * @property \Carbon\Carbon                           $updated_at
 *
 * @property \Illuminate\Database\Eloquent\Collection $social
 *
 * @package Ollieread\Users\Models
 */
class User extends BaseUser
{
    use SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'remember_token',
        'avatar',
        'active',
        'verified',
    ];

    protected $casts = [
        'active'   => 'bool',
        'verified' => 'bool',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function social(): HasMany
    {
        return $this->hasMany(Social::class, 'user_id');
    }
}