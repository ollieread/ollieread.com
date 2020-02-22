@extends('layouts.main')

@section('page.title', 'Articles for ' . ($version->title ?? $version->name))

@section('breadcrumbs')
    <a href="{{ route('site:home') }}" class="breadcrumb">Home</a>
    <a href="{{ route('articles:index') }}" class="breadcrumb">Articles</a>
    <span class="breadcrumb breadcrumb--active">{{ $version->name }}</span>
@endsection

@section('header')
    <header class="page__header">
        @if ($version->docs)
            <div class="page__header-controls">
                <a href="{{ $version->docs }}" class="button button--small" rel="noreferrer noopener" target="_blank">
                    <i class="button__icon fa-book"></i> Documentation
                </a>
            </div>
        @endif
        <h2 class="page__header-heading">Version: {{ $version->heading ?? $version->name }}</h2>
        <p class="page__header-text">
            {{ $version->description }}
        </p>
        @if ($version->release_date)
            <p class="page__header-text">
                <strong>Released:</strong> {{ $version->release_date->format('jS F Y') }}
            </p>
        @endif
    </header>
@endsection

@section('content')

    @include('articles.partials.articles')

@endsection

@section('sidebar')

    @include('articles.partials.sidebar')

@endsection
