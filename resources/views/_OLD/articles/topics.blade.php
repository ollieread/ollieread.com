@extends('layouts.main')

@section('page.title', 'Topics')

@section('breadcrumbs')
    <a href="{{ route('site:home') }}" class="breadcrumb">Home</a>
    <a href="{{ route('articles:index') }}" class="breadcrumb">Articles</a>
    <span class="breadcrumb breadcrumb--active">Topics</span>
@endsection

@section('header')
    <header class="page__header">
        <h2 class="page__header-heading">Topics</h2>
        <p class="page__header-text">
            Topics are used as an extra way to categorise content on the site, tying it to specific uses cases,
            patterns, approaches and subjects.
        </p>
    </header>
@endsection

@section('content')

    @if (isset($topics) && $topics->count())
        @foreach ($topics as $topic)
            <article class="box box--footerless">
                <header class="box__header">
                    <a href="{{ route('articles:topic', $topic->slug) }}"
                       class="box__header-title">{{ $topic->heading ?? $topic->name }}</a>
                </header>
                <main class="box__body">
                    <p>{{ $topic->description }}</p>

                    <a href="{{ route('articles:topic', $topic->slug) }}" class="link">
                        <span class="badge badge--number">{{ $topic->articles_count }}</span>
                        Read Articles
                    </a>
                </main>
            </article>
        @endforeach
    @else
        <article class="box box--footerless">

            <header class="box__header">
                <h2 class="box__header-title">No Versions</h2>
            </header>

            <main class="box__body">
                <p>Weirdly, it appears that there are currently no topics.</p>
            </main>

        </article>
    @endif

    {!! $topics->links('partials.pagination') !!}

@endsection

@section('sidebar')

    @include('articles.partials.sidebar')

@endsection
