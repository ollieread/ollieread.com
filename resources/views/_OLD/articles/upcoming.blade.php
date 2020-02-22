@extends('layouts.main')

@section('page.title', 'Articles')

@section('breadcrumbs')
    <a href="{{ route('site:home') }}" class="breadcrumb">Home</a>
    <a href="{{ route('articles:index') }}" class="breadcrumb">Articles</a>
    <span class="breadcrumb breadcrumb--active">Upcoming</span>
@endsection

@section('header')
    <header class="page__header">
        <h2 class="page__header-heading">Upcoming Articles</h2>
        <p class="page__header-text">
            Here is the list of upcoming articles, scheduled for release in the future.
        </p>
    </header>
@endsection

@section('content')

    @if (isset($articles) && $articles->count())
        @foreach ($articles as $article)
            <article class="box article {{ ! (($article->tags && $article->tags->isNotEmpty()) || ($article->versions && $article->versions->isNotEmpty()) || ($article->topics && $article->topics->isNotEmpty())) ? 'box--footerless' : '' }}">
                <header class="box__header">

                    @if ($article->category)
                        <div class="box__header-bump">
                            <a href="{{ route('articles:category', $article->category->slug) }}"
                               class="category__badge">
                                <i class="category__badge-icon fa-{{ $article->category->icon }}"></i>
                                {{ $article->category->name }}
                            </a>
                        </div>
                    @endif

                    <h2 class="box__header-title">
                        @if ($article->status === \Ollieread\Core\Support\Status::DRAFT)
                            [DRAFT]
                        @elseif ($article->status === \Ollieread\Core\Support\Status::REVIEWING)
                            [REVIEWING]
                        @elseif ($article->status === \Ollieread\Core\Support\Status::PUBLIC)
                            [PUBLIC]
                        @endif
                        {{ $article->heading ?? $article->name }}
                    </h2>

                    @if ($article->series)
                        <a href="#" class="box__header-subtitle">
                            <strong>Series:</strong>
                            {{ $article->series->name }}
                        </a>
                    @endif
                    <time class="article__date">
                        {{ $article->post_at ? $article->post_at->format('jS F Y') : 'TBC' }}
                    </time>
                    {{--<time class="article__reading-time">Read Time: {{ $article->reading_time }}</time>--}}
                </header>

                <main class="box__body">
                    <div class="article__content">
                        <p>{{ $article->excerpt }}</p>
                    </div>
                </main>

                @if (($article->tags && $article->tags->isNotEmpty()) || ($article->versions && $article->versions->isNotEmpty()) || ($article->topics && $article->topics->isNotEmpty()))
                    <footer class="box__footer box__footer--secondary">

                        @if ($article->versions && $article->versions->isNotEmpty())
                            <div class="article__versions">
                                <strong class="article__versions-title">Versions:</strong>
                                <div class="article__versions-list">
                                    @foreach($article->versions as $version)
                                        <a href="{{ route('articles:version', $version->slug) }}" class="version__badge">
                                            <i class="version__badge-icon"></i>
                                            {{ $version->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($article->topics && $article->topics->isNotEmpty())
                            <div class="article__topics">
                                <strong class="article__topics-title">Topics:</strong>
                                <div class="article__topics-list">
                                    @foreach($article->topics as $topic)
                                        <a href="{{ route('articles:topic', $topic->slug) }}" class="topic__badge"
                                           data-tippy-content="{{ $topic->description }}">
                                            <i class="topic__badge-icon"></i>
                                            {{ $topic->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

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
                @endif

            </article>
        @endforeach
    @else
        <article class="box box--footerless">

            <header class="box__header">
                <h2 class="box__header-title">No Upcoming Articles</h2>
            </header>

            <main class="box__body">
                <p>
                    There are no upcoming articles at the moment, but don't worry there will be more soon.
                </p>
            </main>

        </article>
    @endif

    {!! $articles->links('partials.pagination') !!}

@endsection

@section('sidebar')

    @include('articles.partials.sidebar')

@endsection
