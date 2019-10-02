<?php

namespace Ollieread\Core\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Redirect
 *
 * @property-read int            $id
 * @property string              $from
 * @property string              $to
 * @property \Carbon\Carbon|null $expires_at
 * @property \Carbon\Carbon      $created_at
 * @property \Carbon\Carbon      $updated_at
 *
 * @package Ollieread\Models
 */
class Redirect extends Model
{
    protected $table    = 'redirects';

    protected $fillable = [
        'from',
        'to',
        'expires_at',
    ];

    protected $dates    = [
        'expires_at',
    ];
}
