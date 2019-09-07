<?php

namespace Ollieread\Users\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as BaseUser;
use Illuminate\Notifications\Notifiable;
use Ollieread\Users\Mail\Mail;

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
 * @property array                                    $interests
 * @property bool                                     $active
 * @property bool                                     $verified
 * @property \Carbon\Carbon                           $created_at
 * @property \Carbon\Carbon                           $updated_at
 *
 * @property string                                   $gravatar
 *
 * @property \Illuminate\Database\Eloquent\Collection $social
 *
 * @package Ollieread\Users\Models
 */
class User extends BaseUser
{
    use SoftDeletes, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'remember_token',
        'avatar',
        'interests',
        'active',
        'verified',
    ];

    protected $casts = [
        'active'    => 'bool',
        'verified'  => 'bool',
        'interests' => 'array',
    ];

    public function getGravatarAttribute(): string
    {
        return 'https://www.gravatar.com/avatar/'
            . md5(strtolower(trim($this->email)))
            . '?' . http_build_query([
                's' => 72,
                'd' => 'robohash',
                'r' => 'g',
            ]);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id')
                    ->withTimestamps();
    }

    public function sendPasswordResetNotification($token): void
    {
        Mail::forgotPassword($this, $token);
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
