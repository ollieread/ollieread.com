<div id="article-comments" class="comments">

    @if ($comments && $comments->isNotEmpty())
        @each('articles.partials.comment', $comments, 'comment')
    @endif

</div>