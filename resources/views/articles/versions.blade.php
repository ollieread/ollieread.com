@extends('layouts.main')

@section('page.title', 'Versions')

@section('breadcrumbs')
    <a href="{{ route('site:home') }}" class="breadcrumb">Home</a>
    <span class="breadcrumb breadcrumb--active">Versions</span>
@endsection

@section('header')
    <header class="page__header">
        <h2 class="page__header-heading">Versions</h2>
        <p class="page__header-text">
            Versions are used to mark content for particular versions of software, languages or anything else that
            can be versioned.
        </p>
    </header>
@endsection

@section('content')

    @if (isset($versions) && $versions->count())
        @foreach ($versions as $version)
            <article class="box">
                <header class="box__header">
                    <a href="{{ route('articles:version', $version->slug) }}"
                       class="box__header-title">{{ $version->heading ?? $version->name }}</a>
                </header>
                <main class="box__body">
                    <p>{{ $version->description }}</p>

                    <a href="{{ route('articles:version', $version->slug) }}" class="link">
                        <span class="badge badge--number">{{ $version->articles_count }}</span>
                        Read Articles
                    </a>
                </main>
                <footer class="box__footer box__footer--secondary">
                    @if ($version->release_date)
                        <div class="badge">
                            <strong>Released:</strong> {{ $version->release_date->format('jS F Y') }}
                        </div>
                    @endif
                    @if ($version->docs)
                        <a href="{{ $version->docs }}" class="button button--small" target="_blank"
                           rel="noopener noreferrer">
                            <i class="button__icon fa-book"></i> Documentation
                        </a>
                    @endif
                </footer>
            </article>
        @endforeach
    @else
        <article class="box box--footerless">

            <header class="box__header">
                <h2 class="box__header-title">No Versions</h2>
            </header>

            <main class="box__body">
                <p>Weirdly, it appears that there are currently no versions.</p>
            </main>

        </article>
    @endif

    {!! $versions->links('partials.pagination') !!}

@endsection

@section('sidebar')

    @include('articles.partials.sidebar')

@endsection
