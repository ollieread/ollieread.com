@extends('users.admin.layout')

@section('breadcrumbs')
    <a href="{{ route('admin:dashboard') }}" class="breadcrumb">Admin</a>
    <span class="breadcrumb breadcrumb--active">Articles</span>
@endsection

@section('content')

    <header class="page__header">
        <h2 class="page__header-heading">Articles</h2>
    </header>

    @foreach ($articles as $article)
        <article class="column box article">
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

                <a href="{{ route('articles:article', $article->slug) }}"
                   class="box__header-title">{{ $article->heading ?? $article->name }}</a>
                @if ($article->series)
                    <a href="#" class="box__header-subtitle">
                        <strong>Series:</strong>
                        {{ $article->series->name }}
                    </a>
                @endif
                <time class="article__date">{{ $article->post_at->format('jS F Y') }}</time>
                {{--<time class="article__reading-time">Read Time: {{ $article->reading_time }}</time>--}}
            </header>

            @if ($article->image)
                <img src="{{ $article->image }}" alt="" class="box__image">
            @endif

            <main class="box__body">
                <div class="article__content">
                    <p>{{ $article->excerpt }}</p>
                </div>
            </main>

            <footer class="box__footer box__footer--secondary">

                {{--@if ($article->versions && $article->versions->isNotEmpty())
                    <div class="article__versions">
                        <strong class="article__versions-title">Versions:</strong>
                        <div class="article__versions-list">
                            @foreach($article->versions as $version)
                                <a href="#" class="version__badge">
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
                                <a href="#" class="topic__badge" data-tippy-content="{{ $topic->description }}">
                                    <i class="topic__badge-icon"></i>
                                    {{ $topic->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (! $excerpt)
                    @if ($article->tags && $article->tags->isNotEmpty())
                        @foreach($article->tags as $tag)
                            <a href="#" class="tag__badge">
                                <i class="tag__badge-icon"></i>
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    @endif
                @endif--}}

                <div class="button__bar button__bar--slim">
                    <a href="#" class="button button--primary button--small">
                        <i class="button__icon fa-edit"></i> Edit
                    </a>
                    <a href="#" class="button button--primary button--small">
                        @if ($article->active)
                            <i class="button__icon fa-toggle-off"></i> Disable
                        @else
                            <i class="button__icon fa-toggle-on"></i> Enable
                        @endif
                    </a>
                    @if ($article->status !== Ollieread\Core\Support\Status::PRIVATE)
                        <a href="#" class="button button--primary button--small">
                            <i class="button__icon fa-lock"></i> Set Private
                        </a>
                    @endif
                    @if ($article->status !== Ollieread\Core\Support\Status::PUBLIC)
                        <a href="#" class="button button--primary button--small">
                            <i class="button__icon fa-unlock"></i> Set Public
                        </a>
                    @endif
                    <a href="#" class="button button--primary button--small">
                        <i class="button__icon fa-trash"></i> Delete
                    </a>
                </div>

            </footer>

        </article>
    @endforeach

    {!! $articles->links('partials.pagination') !!}

@endsection
