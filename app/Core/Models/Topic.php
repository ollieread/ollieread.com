<?php

namespace Ollieread\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $table = 'topics';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'content',
    ];
}