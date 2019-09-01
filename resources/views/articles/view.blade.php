@extends('layouts.main')

@section('page.title', 'Article - ' . ($article->title ?? $article->name))

@section('breadcrumbs')
    <a href="{{ route('site:home') }}" class="breadcrumb">Home</a>
    <a href="{{ route('articles:index') }}" class="breadcrumb">Articles</a>
    @if ($article->category)
        <a href="{{ route('articles:category', $article->category->slug) }}"
           class="breadcrumb">{{ $article->category->name }}</a>
    @endif
    <span class="breadcrumb breadcrumb--active">{{ $article->name }}</span>
@endsection

@section('content')

    @include('articles.partials.article', ['excerpt' => false])

    <article-comments route="{{ route('articles:article', $article->slug) }}"
                      :authed="{{ auth('user')->user() !== null ? 'true' : 'false' }}"
                      avatar="{{ auth('user')->user() ? auth('user')->user()->avatar ?? auth('user')->user()->gravatar : '' }}"></article-comments>

@endsection

@section('sidebar')

    @include('articles.partials.sidebar')

@endsection
