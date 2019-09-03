@extends('layouts.main')

@section('page.title', 'Articles in ' . ($category->title ?? $category->name))

@section('breadcrumbs')
    <a href="{{ route('site:home') }}" class="breadcrumb">Home</a>
    <a href="{{ route('articles:index') }}" class="breadcrumb">Articles</a>
    <span class="breadcrumb breadcrumb--active">{{ $category->name }}</span>
@endsection

@section('header')
    <header class="page__header">
        <h2 class="page__header-heading">{{ $category->heading ?? $category->name }}</h2>
        <p class="page__header-text">
            {{ $category->description }}
        </p>
    </header>
@endsection

@section('content')

    @include('articles.partials.articles')

@endsection

@section('sidebar')

    @include('articles.partials.sidebar')

@endsection
