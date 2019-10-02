<?php

namespace Ollieread\Articles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ollieread\Users\Models\User;

class Comment extends Model
{
    use SoftDeletes;

    protected $table    = 'article_comments';

    protected $fillable = [
        'comment',
        'private',
        'active',
        'reaction',
        'deleted_reason',
    ];

    protected $casts    = [
        'private' => 'bool',
        'active'  => 'bool',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id')->withDefault();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
