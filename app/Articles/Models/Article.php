<?php

namespace Ollieread\Articles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ollieread\Articles\Support\ArticleMetadata;
use Ollieread\Articles\Support\Markdown;
use Ollieread\Core\Models\Tag;
use Ollieread\Core\Models\Topic;
use Ollieread\Core\Models\Version;

/**
 * Class Article
 *
 * @property-read int                            $id
 * @property int                                 $category_id
 * @property int|null                            $parent_id
 * @property string                              $name
 * @property string|null                         $title
 * @property string|null                         $heading
 * @property string|null                         $seo_description
 * @property string                              $slug
 * @property string|null                         $excerpt
 * @property string                              $content
 * @property string|null                         $image
 * @property bool                                $active
 * @property bool                                $private
 * @property \Carbon\Carbon|null                 $post_at
 * @property \Carbon\Carbon                      $created_at
 * @property \Carbon\Carbon                      $updated_at
 *
 * @property \Ollieread\Articles\Models\Article  $parent
 * @property \Ollieread\Articles\Models\Category $category
 *
 * @package Ollieread\Articles\Models
 */
class Article extends Model
{
    use SoftDeletes;

    protected $table    = 'articles';

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

    protected $casts    = [
        'active' => 'bool',
        'status' => 'int',
    ];

    protected $dates    = [
        'post_at',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'article_id');
    }

    public function getContentParsedAttribute(): string
    {
        return Markdown::parse($this->getAttributeValue('content'));
    }

    public function getKeywordsAttribute()
    {
        return $this->tags
            ->merge($this->topics)
            ->merge($this->versions)
            ->pluck('name')
            ->implode(', ');
    }

    public function getMetadataAttribute(): string
    {
        return new ArticleMetadata($this);
    }

    public function getReadingTimeAttribute(): string
    {
        $word    = str_word_count(strip_tags($this->content));
        $minutes = ceil($word / 200);

        return $minutes . ' minute' . ($minutes > 1 ? 's' : '');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class, 'series_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tags', 'article_id', 'tag_id');
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'article_topics', 'article_id', 'topic_id');
    }

    public function versions(): BelongsToMany
    {
        return $this->belongsToMany(Version::class, 'article_versions', 'article_id', 'version_id');
    }
}
