<?php

namespace Ollieread\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ollieread\Articles\Models\Article;

class Version extends Model
{
    protected $table    = 'versions';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'docs',
        'release_date',
    ];

    protected $dates    = [
        'release_date',
    ];

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_versions', 'version_id', 'article_id');
    }
}
