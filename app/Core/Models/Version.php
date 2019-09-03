<?php

namespace Ollieread\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $table = 'versions';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'docs',
        'release_date',
    ];

    protected $dates = [
        'release_date',
    ];
}