<?php

namespace Ollieread\Articles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table    = 'categories';

    protected $fillable = [
        'name',
        'title',
        'heading',
        'seo_description',
        'slug',
        'icon',
        'description',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id');
    }
}
