<div class="share">

    <div class="share__text">
        Share to:
    </div>

    <div class="button__bar">

        <a href="https://www.facebook.com/sharer/sharer.php?u={{ url('/p/' . $article->encoded_id) }}" target="_blank" rel="noreferrer noopener nofollow"
           class="button button--small button--icon">
            <i class="button__icon button__icon--facebook"></i>
        </a>

        <a href="https://twitter.com/intent/tweet?text={{ $article->name }} by @ollieread {{ url('/p/' . $article->encoded_id) }}" target="_blank" rel="noreferrer noopener nofollow"
           class="button button--small button--icon">
            <i class="button__icon button__icon--twitter"></i>
        </a>

        <a href="https://www.reddit.com/submit?url={{ url('/p/' . $article->encoded_id) }}&title={{ $article->name }} by /u/ollieread" target="_blank" rel="noreferrer noopener nofollow"
           class="button button--small button--icon">
            <i class="button__icon button__icon--reddit"></i>
        </a>

    </div>

</div>
