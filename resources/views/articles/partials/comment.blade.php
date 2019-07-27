<article class="comment">
    <div class="comment__author">
        <div class="avatar">
            <img src="{{ $comment->author->avatar }}" alt=""
                 class="avatar__image">
        </div>
    </div>

    <div class="comment__message">
        <main class="comment__message-body comment__message-body--headerless">
            @markdown($comment->comment)
        </main>

        <footer class="comment__message-footer">
            <strong>{{ $comment->author->username }}</strong>
            <em class="comment__date">{{ $comment->created_at->format('H:ia \o\n js F Y') }}</em>
        </footer>
    </div>
</article>

@if ($comment->replies && $comment->replies->isNotEmpty())
    <div class="comment__thread">
        @each('articles.partials.comment', $comment->replies, 'comment')
    </div>
@endif