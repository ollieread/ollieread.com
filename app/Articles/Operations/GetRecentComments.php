<?php

namespace Ollieread\Articles\Operations;

use Ollieread\Articles\Models\Comment;

class GetRecentComments
{
    public function perform()
    {
        return Comment::query()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }
}
