<?php

namespace Ollieread\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'ident',
        'description',
        'active',
        'level',
        'permissions',
    ];

    protected $casts = [
        'active' => 'bool',
        'level'  => 'int',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }
}
