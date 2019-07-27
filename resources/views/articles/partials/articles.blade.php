@if (isset($articles) && $articles->count())
    @foreach ($articles as $article)
        @include('articles.partials.article', ['excerpt' => true])
    @endforeach
@else
    @include('articles.partials.none')
@endif