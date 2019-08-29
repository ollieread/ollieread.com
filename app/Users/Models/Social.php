<?php

namespace Ollieread\Users\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Social
 *
 * @property-read int            $id
 * @property string              $provider
 * @property string              $social_id
 * @property string              $token
 * @property string|null         $refresh_token
 * @property string|null         $secret
 * @property array|null          $metadata
 * @property string              $avatar
 * @property bool                $use_avatar
 * @property null|\Carbon\Carbon $expires_at
 * @property \Carbon\Carbon      $created_at
 * @property \Carbon\Carbon      $updated_at
 *
 * @property string              $username
 *
 * @package Ollieread\Users\Models
 */
class Social extends Model
{
    protected $table = 'user_social';

    protected $fillable = [
        'provider',
        'social_id',
        'token',
        'refresh_token',
        'expires_in',
        'secret',
        'metadata',
        'avatar',
        'use_avatar',
    ];

    protected $casts = [
        'metadata'   => 'json',
        'use_avatar' => 'bool',
    ];

    protected $dates = [
        'expires_at',
    ];

    public function getUsernameAttribute(): string
    {
        if ($this->provider === 'discord') {
            return $this->metadata['username'] . '#' . $this->metadata['discriminator'];
        }

        return $this->metadata['username'];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
