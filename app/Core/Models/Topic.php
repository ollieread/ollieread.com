<?php

namespace Ollieread\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ollieread\Articles\Models\Article;

class Topic extends Model
{
    protected $table    = 'topics';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'content',
    ];

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_topics', 'topic_id', 'article_id');
    }
}
