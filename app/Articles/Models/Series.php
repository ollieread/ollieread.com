<?php

namespace Ollieread\Articles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ollieread\Core\Models\Tag;
use Ollieread\Core\Models\Topic;
use Ollieread\Core\Models\Version;

class Series extends Model
{
    protected $table = 'series';

    protected $fillable = [
        'name',
        'title',
        'heading',
        'seo_description',
        'slug',
        'excerpt',
        'content',
        'active',
        'status',
        'post_at',
    ];

    protected $casts = [
        'active' => 'bool',
        'status' => 'int',
    ];

    protected $dates = [
        'post_at',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function series(): HasMany
    {
        return $this->hasMany(Article::class, 'series_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'series_tags', 'series_id', 'tag_id');
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'series_topics', 'series_id', 'topic_id');
    }

    public function versions(): BelongsToMany
    {
        return $this->belongsToMany(Version::class, 'series_versions', 'series_id', 'version_id');
    }
}