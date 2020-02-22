@extends('layouts.main')

@section('page.title', 'Articles on the topic of ' . ($topic->title ?? $topic->name))

@section('breadcrumbs')
    <a href="{{ route('site:home') }}" class="breadcrumb">Home</a>
    <a href="{{ route('articles:index') }}" class="breadcrumb">Articles</a>
    <span class="breadcrumb breadcrumb--active">{{ $topic->name }}</span>
@endsection

@section('header')
    <header class="page__header">
        <h2 class="page__header-heading">Topic: {{ $topic->heading ?? $topic->name }}</h2>
        <p class="page__header-text">
            {{ $topic->content ?? $topic->description }}
        </p>
    </header>
@endsection

@section('content')

    @include('articles.partials.articles')

@endsection

@section('sidebar')

    @include('articles.partials.sidebar')

@endsection
