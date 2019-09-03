@extends('layouts.main')

@section('content')

    <article class="box article">

        <header class="box__header">

            <div class="box__header-bump">
                <a href="#" class="category__badge">
                    <i class="category__badge-icon fa-folder"></i>
                    Category
                </a>
            </div>

            <a href="#"
               class="box__header-title">Test Article</a>
            <a href="#" class="box__header-subtitle">
                <strong>Series:</strong>
                Test Series
            </a>
            <time class="article__date">24th June 1988</time>
        </header>

        <img src="https://placeimg.com/950/300/any" alt="" class="box__image">

        <main class="box__body">
            <div class="article__content">

                @markdown(file_get_contents(resource_path('/views/articles/partials/test.blade.php')))

            </div>
        </main>
        <footer class="box__footer box__footer--secondary">

            <div class="article__versions">
                <strong class="article__versions-title">Versions:</strong>
                <div class="article__versions-list">
                    <a href="#" class="version__badge">
                        <i class="version__badge-icon"></i>
                        Version
                    </a>
                    <a href="#" class="version__badge">
                        <i class="version__badge-icon"></i>
                        Version
                    </a>
                </div>
            </div>

            <div class="article__topics">
                <strong class="article__topics-title">Topics:</strong>
                <div class="article__topics-list">
                    <a href="#" class="topic__badge">
                        <i class="topic__badge-icon"></i>
                        Topic
                    </a>
                    <a href="#" class="topic__badge">
                        <i class="topic__badge-icon"></i>
                        Topic
                    </a>
                    <a href="#" class="topic__badge">
                        <i class="topic__badge-icon"></i>
                        Topic
                    </a>
                    <a href="#" class="topic__badge">
                        <i class="topic__badge-icon"></i>
                        Topic
                    </a>
                </div>
            </div>

            {{--@if (! $excerpt)
                @if ($article->tags && $article->tags->isNotEmpty())
                    @foreach($article->tags as $tag)
                        <a href="#" class="tag__badge">
                            <i class="tag__badge-icon"></i>
                            {{ $tag->name }}
                        </a>
                    @endforeach
                @endif
            @endif--}}

        </footer>

    </article>

@endsection

@section('sidebar')

    @include('articles.partials.sidebar')

@endsection