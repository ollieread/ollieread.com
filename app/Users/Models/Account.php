<?php

namespace Ollieread\Users\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table    = 'accounts';

    protected $fillable = [
        'stripe_id',
        'company',
        'vat_number',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'account_users', 'account_id', 'user_id')
            ->withPivot('permissions');
    }
}
