@extends('layouts.main')

@push('page.header')
    @include('partials.main-meta')
@endpush

@section('content')

    <h1 class="page__heading">
        <i class="page__heading-icon fa-{{ $category->icon }}"></i>
        {{ $category->heading ?? $category->name }}
    </h1>

    <p class="page__description">{{ $category->description }}</p>

    @include('articles.partials.articles')

@endsection
