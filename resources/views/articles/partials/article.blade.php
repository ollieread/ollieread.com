<article class="box article {{ ! (($article->tags && $article->tags->isNotEmpty()) || ($article->versions && $article->versions->isNotEmpty()) || ($article->topics && $article->topics->isNotEmpty())) ? 'box--footerless' : '' }}">

    <header class="box__header">

        @if ($article->category)
            <div class="box__header-bump">
                <a href="{{ route('articles:category', $article->category->slug) }}" class="category__badge">
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
        <time class="article__date">{{ $article->post_at ? $article->post_at->format('jS F Y') : '12th Never 9999' }}</time>
        {{--<time class="article__reading-time">Read Time: {{ $article->reading_time }}</time>--}}
    </header>

    @if ($article->image)
        <picture>
            <img
                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url('images/banner_' . $article->image) }}"
                srcset="{{ \Illuminate\Support\Facades\Storage::disk('public')->url('images/banner_' . $article->image) }},
{{ \Illuminate\Support\Facades\Storage::disk('public')->url('images/banner_2x_' . $article->image) }} 2x"
                class="box__image">
        </picture>
    @endif

    <main class="box__body">
        <div class="article__content">
            @if ($excerpt)
                <p>{{ $article->excerpt }}</p>

                <p><a href="{{ route('articles:article', $article->slug) }}">Read More</a></p>
            @else
                {!! $article->content_parsed !!}
            @endif
        </div>

        @if (! $excerpt)
            @include('articles.partials.social')
        @endif
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
                            <a href="{{ route('articles:topic', $topic->slug) }}" class="topic__badge" data-tippy-content="{{ $topic->description }}">
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
