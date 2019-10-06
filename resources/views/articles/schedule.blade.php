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
            <article class="box article box--footerless box--bodyless">
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
                        <i class="article__lock"></i>
                        {{ $article->heading ?? $article->name }}
                    </h2>

                    @if ($article->series)
                        <a href="#" class="box__header-subtitle">
                            <strong>Series:</strong>
                            {{ $article->series->name }}
                        </a>
                    @endif
                    <time
                        class="article__date">{{ $article->post_at ? $article->post_at->format('jS F Y') : '12th Never 9999' }}</time>
                    {{--<time class="article__reading-time">Read Time: {{ $article->reading_time }}</time>--}}
                </header>
            </article>
        @endforeach
    @else
        <article class="box box--footerless">

            <header class="box__header">
                <h2 class="box__header-title">No Upcoming Articles</h2>
            </header>

            <main class="box__body">
                <p>
                    There are no upcoming articles at the moment, but don't worry, articles will only appear on this
                    page once they leave the draft stage, so there will be more soon.
                </p>
            </main>

        </article>
    @endif

    {!! $articles->links('partials.pagination') !!}


@endsection

@section('sidebar')

    @include('articles.partials.sidebar')

@endsection
